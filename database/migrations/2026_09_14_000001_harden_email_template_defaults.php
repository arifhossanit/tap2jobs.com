<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->updateTemplate('Job Notification', function (object $template): array {
            return [
                'variables' => $this->appendVariable($this->appendVariable($template->variables, '{{date}}'), '{{jobs}}'),
            ];
        });

        $this->updateTemplate('News Letter', function (object $template): array {
            return ['subject' => $template->subject ?: 'New Notice from {{from_name}}'];
        });

        $this->replaceInTemplate('Contact Us', [
            'Quaerat facere dicta<br/><br>' => '',
        ]);

        $this->replaceInTemplate('Email Job To Friend', [
            'I have send you the below job link in which you can find the relevant details for the same.'
                => 'I have sent you the job link below, where you can find the relevant details.',
        ]);

        $this->replaceInTemplate('Job Alert', [
            '{{job_name}}' => '{{candidate_name}}',
        ], true);

        $this->replaceInTemplate('Candidate Job Applied', [
            'I have go through with your job details and thereby i have applied for the same. Please kindly contact me if i found suitable based on your needs.'
                => 'I reviewed the job details and have applied for this position. Please contact me if my profile matches your requirements.',
        ]);

        $this->updateTemplate('Password Reset Email', function (object $template): array {
            return [
                'body' => str_replace(
                    'This password reset link will expire in 60 minutes.',
                    'This password reset link will expire in {{reset_expire_minutes}} minutes.',
                    $template->body
                ),
                'variables' => $this->appendVariable($template->variables, '{{reset_expire_minutes}}'),
            ];
        });
    }

    public function down(): void
    {
        // Content edits are intentionally not reversed to avoid overwriting administrator customizations.
    }

    private function replaceInTemplate(string $name, array $replacements, bool $replaceVariables = false): void
    {
        $this->updateTemplate($name, function (object $template) use ($replacements, $replaceVariables): array {
            $updates = ['body' => str_replace(array_keys($replacements), array_values($replacements), $template->body)];
            if ($replaceVariables) {
                $updates['variables'] = str_replace(array_keys($replacements), array_values($replacements), $template->variables);
            }

            return $updates;
        });
    }

    private function updateTemplate(string $name, callable $callback): void
    {
        $template = DB::table('email_templates')->where('template_name', $name)->first();
        if ($template) {
            DB::table('email_templates')->where('id', $template->id)->update($callback($template));
        }
    }

    private function appendVariable(string $variables, string $variable): string
    {
        return str_contains($variables, $variable) ? $variables : rtrim($variables).', '.$variable;
    }
};