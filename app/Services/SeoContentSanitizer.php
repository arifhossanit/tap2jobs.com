<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

class SeoContentSanitizer
{
    public function sanitize(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('Cache.DefinitionImpl', null);
        $config->set('HTML.Allowed', 'p,h2,h3,h4,strong,b,em,i,ul,ol,li,br,blockquote,a[href|title]');
        $config->set('URI.DisableExternalResources', true);
        $config->set('URI.DisableResources', true);

        return (new HTMLPurifier($config))->purify(
            html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8')
        );
    }
}
