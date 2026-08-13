<?php

namespace Database\Seeders;

use App\Models\FAQ;
use App\Models\FAQCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployerFAQSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'General Questions',
                'icon' => 'fa-solid fa-gear fa-fw',
                'faqs' => [
                    ['How do I create an employer account?', 'Click Employer Register, fill in your company and recruiter information, then submit the form to create your employer account.'],
                    ['Can I update my company information?', 'Yes. After logging in, open your company profile and update company details, contact information, address, industry, and branding information.'],
                    ['What should I do if I forget my employer password?', 'Use the Forgot Password link from the employer login page and follow the reset instructions sent to your registered email address.'],
                ],
            ],
            [
                'name' => 'Post Jobs',
                'icon' => 'fa-solid fa-briefcase fa-fw',
                'faqs' => [
                    ['How do I post a job?', 'Log in to your employer panel, open the jobs section, click Post Job, fill in the job details, and submit it for publishing or review.'],
                    ['Can I save a job as draft?', 'Yes. If the draft flow is enabled, you can save incomplete job posts and complete them later from your employer dashboard.'],
                    ['Can I edit a published job?', 'You can edit job details from the employer jobs list. Some changes may require admin review depending on platform settings.'],
                ],
            ],
            [
                'name' => 'Applicants',
                'icon' => 'fa-solid fa-users fa-fw',
                'faqs' => [
                    ['Where can I see applicants?', 'Open your employer dashboard or job applications area to see candidates who applied to your posted jobs.'],
                    ['How do I shortlist a candidate?', 'Open an application, review the candidate profile or CV, and move the candidate to the appropriate hiring stage.'],
                    ['Can I download candidate resumes?', 'If resume access is enabled for your account and job, you can view or download candidate resumes from the application details.'],
                ],
            ],
            [
                'name' => 'Candidate Search',
                'icon' => 'fa-solid fa-magnifying-glass fa-fw',
                'faqs' => [
                    ['How do I search candidates?', 'Use candidate search filters such as keyword, location, skills, experience, career level, and other available profile criteria.'],
                    ['Can I save favorite candidates?', 'Yes. You can save candidates to your favorite list so you can review or contact them later.'],
                    ['Why are some candidate details hidden?', 'Some details may be limited based on privacy settings, subscription rules, or platform access permissions.'],
                ],
            ],
            [
                'name' => 'Interview',
                'icon' => 'fa-solid fa-calendar-check fa-fw',
                'faqs' => [
                    ['How do I invite a candidate for interview?', 'Open the candidate application, choose the interview or schedule option, add the details, and send the invitation.'],
                    ['Can I create interview time slots?', 'Yes. You can create available schedule slots for candidates when the interview scheduling feature is enabled.'],
                    ['How will candidates receive interview updates?', 'Candidates receive interview updates through their dashboard and may also receive email or platform notifications.'],
                ],
            ],
            [
                'name' => 'Company Profile',
                'icon' => 'fa-solid fa-building fa-fw',
                'faqs' => [
                    ['Why should I complete my company profile?', 'A complete company profile builds trust with candidates and helps them understand your organization before applying.'],
                    ['Can I upload a company logo?', 'Yes. Open company profile settings and upload or replace your logo and company images from the branding area.'],
                    ['How do I update company address and contact info?', 'Edit the company profile fields and save the updated billing, office, and recruiter contact information.'],
                ],
            ],
            [
                'name' => 'Billing',
                'icon' => 'fa-solid fa-credit-card fa-fw',
                'faqs' => [
                    ['Where can I see invoices?', 'Invoices and transaction details are available in your employer billing or transactions section.'],
                    ['How do I buy a plan?', 'Choose a suitable subscription or job posting plan, select a payment method, and complete checkout from the employer panel.'],
                    ['Can I use manual payment?', 'If manual payment is enabled, select the manual payment option and follow the instructions shown during checkout.'],
                ],
            ],
            [
                'name' => 'Support',
                'icon' => 'fa-solid fa-headset fa-fw',
                'faqs' => [
                    ['How do I contact employer support?', 'Use the Contact Us page or available support channel and include your company name, registered email, and issue details.'],
                    ['How do I report a technical issue?', 'Send support the issue details, screenshots if available, your browser/device information, and the page where the issue happened.'],
                    ['Can support help me improve a job post?', 'Support can guide you on required fields and posting rules, but final job content should reflect your actual hiring needs.'],
                ],
            ],
        ];

        foreach ($categories as $categoryIndex => $categoryData) {
            $category = FAQCategory::updateOrCreate(
                [
                    'slug' => 'employer-'.Str::slug($categoryData['name']),
                    'audience' => 'employer',
                ],
                [
                    'name' => $categoryData['name'],
                    'icon' => $categoryData['icon'],
                    'sort_order' => $categoryIndex + 1,
                ]
            );

            foreach ($categoryData['faqs'] as $faqIndex => [$title, $description]) {
                FAQ::updateOrCreate(
                    ['title' => $title],
                    [
                        'faq_category_id' => $category->id,
                        'description' => $description,
                        'sort_order' => $faqIndex + 1,
                    ]
                );
            }
        }
    }
}
