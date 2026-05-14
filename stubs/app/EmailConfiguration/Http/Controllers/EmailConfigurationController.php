<?php

namespace App\EmailConfiguration\Http\Controllers;

use App\EmailConfiguration\Http\Requests\IndexEmailConfigurationRequest;
use App\EmailConfiguration\Http\Requests\StoreEmailConfigurationRequest;
use App\EmailConfiguration\Http\Requests\TestSendEmailRequest;
use App\EmailConfiguration\Http\Requests\UpdateEmailConfigurationRequest;
use App\EmailConfiguration\Models\EmailConfiguration;
use App\EmailConfiguration\Services\EmailTemplateRenderer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailConfigurationController extends Controller
{
    public function __construct(
        protected EmailTemplateRenderer $renderer
    ) {
    }

    public function index(IndexEmailConfigurationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $defaultPerPage = max(1, (int) config('email-configuration.per_page', 15));
        $maxPerPage = max(1, (int) config('email-configuration.per_page_max', 100));
        $perPage = isset($validated['per_page'])
            ? min(max((int) $validated['per_page'], 1), $maxPerPage)
            : $defaultPerPage;

        $query = EmailConfiguration::query()
            ->with(['createdBy', 'updatedBy'])
            ->orderBy('id');

        $search = isset($validated['search']) ? trim((string) $validated['search']) : '';
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereLike('name', $search)
                    ->orWhereLike('subject', $search)
                    ->orWhereLike('slug', $search);
            });
        }

        if (! empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        if (array_key_exists('is_active', $validated) && $validated['is_active'] !== null) {
            $query->where('is_active', (bool) $validated['is_active']);
        }

        return response()->json($query->paginate($perPage));
    }

    public function store(StoreEmailConfigurationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $userId = Auth::id();

        if ($userId !== null) {
            $data['created_by'] = $userId;
            $data['updated_by'] = $userId;
        }

        $configuration = EmailConfiguration::query()->create($data);

        return response()->json(
            $configuration->loadMissing(['createdBy', 'updatedBy']),
            Response::HTTP_CREATED
        );
    }

    public function show(int $id): JsonResponse
    {
        $configuration = EmailConfiguration::query()
            ->with(['createdBy', 'updatedBy'])
            ->findOrFail($id);

        return response()->json($configuration);
    }

    public function update(UpdateEmailConfigurationRequest $request, int $id): JsonResponse
    {
        $configuration = EmailConfiguration::query()->findOrFail($id);
        $data = $request->validated();
        $userId = Auth::id();

        if ($userId !== null) {
            $data['updated_by'] = $userId;
        }

        $configuration->update($data);

        return response()->json($configuration->fresh(['createdBy', 'updatedBy']));
    }

    public function destroy(int $id): JsonResponse
    {
        $configuration = EmailConfiguration::query()->findOrFail($id);
        $configuration->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function testSend(TestSendEmailRequest $request, int $id): JsonResponse
    {
        try {
            $configuration = EmailConfiguration::query()->findOrFail($id);
            $to = $request->validated('to');
            $variables = $request->validated('variables') ?? [];

            $subject = $this->renderer->render($configuration->subject, $variables);
            $html = $this->renderer->render($configuration->html_content, $variables);
            $text = $configuration->text_content !== null
                ? $this->renderer->render($configuration->text_content, $variables)
                : null;

            Mail::send([], [], function ($message) use ($to, $subject, $html, $text) {
                $message->to($to)->subject($subject)->html($html);

                if ($text !== null && $text !== '') {
                    $message->text($text);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
