<?php

namespace App\Services;

use DOMDocument;
use InvalidArgumentException;
use Rhukster\DomSanitizer\DOMSanitizer;
use Throwable;

class SvgSanitizer
{
    public function sanitize(string $svg): string
    {
        try {
            $sanitized = (new DOMSanitizer(DOMSanitizer::SVG))->sanitize($svg);
        } catch (Throwable) {
            throw new InvalidArgumentException(__('messages.ad.invalid_svg_message'));
        }

        if ($sanitized === '') {
            throw new InvalidArgumentException(__('messages.ad.invalid_svg_message'));
        }

        $document = new DOMDocument();
        $previousErrors = libxml_use_internal_errors(true);

        try {
            $loaded = $document->loadXML($sanitized, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousErrors);
        }

        if (! $loaded || strtolower((string) $document->documentElement?->localName) !== 'svg') {
            throw new InvalidArgumentException(__('messages.ad.invalid_svg_message'));
        }

        foreach ($document->getElementsByTagName('*') as $element) {
            foreach (['href', 'xlink:href'] as $attribute) {
                $value = trim($element->getAttribute($attribute));

                if ($value !== '' && ! str_starts_with($value, '#')) {
                    $element->removeAttribute($attribute);
                }
            }

            $xlinkValue = trim($element->getAttributeNS('http://www.w3.org/1999/xlink', 'href'));
            if ($xlinkValue !== '' && ! str_starts_with($xlinkValue, '#')) {
                $element->removeAttributeNS('http://www.w3.org/1999/xlink', 'href');
            }
        }

        return (string) $document->saveXML($document->documentElement);
    }
}
