<?php

namespace Tests\Unit;

use Tests\TestCase;

class SharedDashboardFooterTest extends TestCase
{
    public function test_front_candidate_and_employer_use_the_same_footer_component(): void
    {
        $frontFooter = file_get_contents(resource_path('views/front_web/layouts/footer.blade.php'));
        $candidateLayout = file_get_contents(resource_path('views/candidate/layouts/app.blade.php'));
        $employerLayout = file_get_contents(resource_path('views/employer/layouts/app.blade.php'));
        $legacyEmployerFooter = file_get_contents(resource_path('views/employer/layouts/footer.blade.php'));

        $this->assertStringContainsString('front-shared-footer', $frontFooter);
        $this->assertStringContainsString("@include('front_web.layouts.footer')", $candidateLayout);
        $this->assertStringContainsString("@include('front_web.layouts.footer')", $employerLayout);
        $this->assertSame("@include('front_web.layouts.footer')", trim($legacyEmployerFooter));
    }

    public function test_dashboard_layouts_load_the_self_contained_footer_styles(): void
    {
        $candidateLayout = file_get_contents(resource_path('views/candidate/layouts/app.blade.php'));
        $employerLayout = file_get_contents(resource_path('views/employer/layouts/app.blade.php'));
        $footerStyles = file_get_contents(resource_path('assets/front_web_css/footer.css'));

        $this->assertStringContainsString("mix('css/footer.css')", $candidateLayout);
        $this->assertStringContainsString("mix('css/footer.css')", $employerLayout);
        $this->assertStringContainsString('footer.front-shared-footer h3.fs-18', $footerStyles);
        $this->assertStringContainsString('footer.front-shared-footer .fs-14', $footerStyles);
        $this->assertStringContainsString('footer.front-shared-footer .footer-info__block', $footerStyles);
        $this->assertStringContainsString('footer.front-shared-footer > .container', $footerStyles);
        $this->assertStringContainsString('height: auto !important', $footerStyles);
    }
}
