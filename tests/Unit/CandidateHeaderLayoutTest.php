<?php

namespace Tests\Unit;

use Tests\TestCase;

class CandidateHeaderLayoutTest extends TestCase
{
    public function test_candidate_header_matches_employer_header_structure(): void
    {
        $header = file_get_contents(resource_path('views/candidate/layouts/header.blade.php'));

        $this->assertStringContainsString('id="candidateLanguageDropdown"', $header);
        $this->assertStringContainsString('candidate-dashboard-header', $header);
        $this->assertStringContainsString('candidate-header-language-option', $header);
        $this->assertStringContainsString('id="candidateNotificationDropdown"', $header);
        $this->assertStringContainsString('id="candidateUserDropdown"', $header);
        $this->assertStringContainsString('dropdown-menu p-4 pb-4', $header);
        $this->assertStringNotContainsString('changeLanguageModal', $header);
        $this->assertStringNotContainsString('id="dropdownMenuButton1"', $header);
        $this->assertStringContainsString("getNotification(\\App\\Models\\Notification::CANDIDATE)", $header);
        $this->assertStringContainsString("@include('candidate.layouts.sidebar')", $header);
        $this->assertStringContainsString("route('candidate.profile')", $header);
        $this->assertStringContainsString('href="#changePasswordModal"', $header);
        $this->assertStringNotContainsString('href="javascript:void(0)" class="dropdown-item text-gray-900 editCandidateProfileModal', $header);

        $styles = file_get_contents(resource_path('assets/sass/new-custom.scss'));
        $this->assertStringContainsString('.candidate-dashboard-header', $styles);
        $this->assertStringContainsString('flex: 0 0 20px', $styles);
        $this->assertStringContainsString('line-height: 20px', $styles);
    }

    public function test_candidate_header_overflow_menu_uses_valid_destinations(): void
    {
        $sidebar = file_get_contents(resource_path('views/candidate/layouts/sidebar.blade.php'));

        $this->assertStringContainsString("route('candidate.applied.job')", $sidebar);
        $this->assertStringContainsString("route('candidate.job.alert')", $sidebar);
        $this->assertStringContainsString("Request::is('candidate/applied-jobs*')", $sidebar);
        $this->assertStringNotContainsString('href="javascript:void(0)"', $sidebar);
    }

    public function test_employer_header_uses_the_same_icon_and_label_alignment(): void
    {
        $header = file_get_contents(resource_path('views/employer/layouts/header.blade.php'));
        $styles = file_get_contents(resource_path('assets/sass/new-custom.scss'));

        $this->assertStringContainsString('employer-dashboard-header', $header);
        $this->assertStringContainsString('$notifications = getNotification(\App\Models\Notification::EMPLOYER);', $header);
        $this->assertStringContainsString('$notificationCount = $notifications->count();', $header);
        $this->assertStringNotContainsString('@php($notificationCount', $header);
        $this->assertStringContainsString('$loggedInEmployer->company?->contact_person_name', $header);
        $this->assertStringContainsString('{{ $employerHeaderName }}', $header);
        $this->assertStringNotContainsString('{{\Illuminate\Support\Facades\Auth::user()->full_name}}', $header);
        $this->assertStringContainsString('.employer-dashboard-header', $styles);
        $this->assertStringContainsString('[dir="rtl"] .employer-dashboard-header', $styles);
    }

    public function test_candidate_profile_tabs_wrap_without_horizontal_scrolling(): void
    {
        $menu = file_get_contents(resource_path('views/candidate/profile/profile_menu.blade.php'));
        $styles = file_get_contents(resource_path('assets/sass/new-custom.scss'));

        $this->assertStringNotContainsString('candidate-profile-menu__top overflow-auto', $menu);
        $this->assertStringNotContainsString('candidate-profile-menu__sub overflow-auto', $menu);
        $this->assertStringContainsString('grid-template-columns: repeat(6, minmax(0, 1fr))', $styles);
        $this->assertStringContainsString('grid-template-columns: repeat(3, minmax(0, 1fr))', $styles);
        $this->assertStringContainsString('grid-template-columns: repeat(2, minmax(0, 1fr))', $styles);
        $this->assertStringContainsString('flex-wrap: wrap', $styles);
        $this->assertStringContainsString('gap: 4px 18px', $styles);

        $profileViews = [
            'personal-information.blade.php',
            'education-training.blade.php',
            'employment.blade.php',
            'other-information.blade.php',
            'accomplishment.blade.php',
        ];

        $this->assertStringContainsString('window.scrollCandidateProfileSection = function', $menu);
        $this->assertStringContainsString('stickyTop + menuHeight', $menu);
        foreach ($profileViews as $profileView) {
            $view = file_get_contents(resource_path('views/candidate/profile/'.$profileView));
            $this->assertStringContainsString('window.scrollCandidateProfileSection(', $view);
        }
    }
}
