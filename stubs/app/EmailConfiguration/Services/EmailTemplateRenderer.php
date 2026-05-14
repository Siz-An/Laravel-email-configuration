<?php

namespace App\EmailConfiguration\Services;

class EmailTemplateRenderer
{
    /**
     * @param  array<string, mixed>  $variables
     */
    public function render(string $content, array $variables): string
    {
        $result = $content;

        foreach ($variables as $key => $value) {
            $result = str_replace('{{'.$key.'}}', (string) $value, $result);
        }

        return $result;
    }
}
