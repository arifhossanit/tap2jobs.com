<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class EmailTemplateWorkflowTest extends TestCase
{
    public function test_template_identity_and_short_codes_are_protected(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/EmailTemplateController.php'));
        $request = file_get_contents(app_path('Http/Requests/UpdateEMailTemplateRequest.php'));
        $view = file_get_contents(resource_path('views/email_templates/edit.blade.php'));

        $this->assertStringContainsString('$request->validated()', $controller);
        $this->assertStringNotContainsString('$request->all()', $controller);
        $this->assertStringContainsString("'readonly'", $view);
        $this->assertStringContainsString('Unknown short code(s)', $request);
        $this->assertStringContainsString('not_regex:/[<>]/', $request);
        $this->assertStringContainsString("'body' => ['required', 'string']", $request);
    }

    public function test_all_mailables_use_the_template_subject_with_a_safe_fallback(): void
    {
        $mailables = [
            'EmailToCandidate.php' => 'New Job Alert',
            'EmailToEmployer.php' => 'Job Applied by Candidate',
            'EmailJobToFriend.php' => 'New Job Details',
            'JobNotification.php' => 'New Job Notification',
        ];

        foreach ($mailables as $file => $fallback) {
            $source = file_get_contents(app_path('Mail/'.$file));
            $this->assertStringContainsString("['subject'] ?? '".$fallback."'", $source);
        }

        $newsletter = file_get_contents(app_path('Mail/NewsLetterMail.php'));
        $this->assertStringContainsString("['subject'] ??", $newsletter);
    }

    public function test_default_templates_declare_every_supported_short_code(): void
    {
        $seeder = file_get_contents(database_path('seeders/EmailTemplateSeeder.php'));

        $this->assertStringContainsString('{{date}}, {{jobs}}', $seeder);
        $this->assertStringContainsString('{{reset_expire_minutes}}', $seeder);
        $this->assertStringContainsString("'subject' => 'New Notice from {{from_name}}'", $seeder);
        $this->assertStringNotContainsString('{{job_name}}', $seeder);
        $this->assertStringNotContainsString('Quaerat facere dicta', $seeder);
        $this->assertStringNotContainsString('I have send you', $seeder);
        $this->assertStringNotContainsString('I have go through', $seeder);
    }

    public function test_missing_critical_templates_have_safe_handling(): void
    {
        $files = [
            app_path('Notifications/PasswordReset.php'),
            app_path('Jobs/CandidatesAlertJob.php'),
            app_path('Repositories/JobRepository.php'),
            app_path('Repositories/NoticeboardRepository.php'),
        ];

        foreach ($files as $file) {
            $this->assertStringContainsString('if (! $templateBody)', file_get_contents($file));
        }
    }
}