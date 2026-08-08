<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMXPath;
use RuntimeException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class ResumePreviewService
{
    public function preview(Media $media): Response
    {
        if ($media->mime_type === 'application/pdf' || str_starts_with($media->mime_type, 'image/')) {
            return response()->file($media->getPath(), [
                'Content-Type' => $media->mime_type,
                'Content-Disposition' => 'inline; filename="'.str_replace('"', '', $media->file_name).'"',
                'Cache-Control' => 'private, no-store, max-age=0',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        }

        if (strtolower($media->extension) === 'docx') {
            return response($this->docxHtml($media), 200, [
                'Content-Type' => 'text/html; charset=UTF-8',
                'Content-Disposition' => 'inline',
                'Cache-Control' => 'private, no-store, max-age=0',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        }

        abort(415);
    }

    private function docxHtml(Media $media): string
    {
        $zip = new ZipArchive();

        if ($zip->open($media->getPath()) !== true) {
            throw new RuntimeException('Unable to open the CV document.');
        }

        $documentXml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($documentXml === false) {
            throw new RuntimeException('The CV document is invalid.');
        }

        $document = new DOMDocument();
        $previousErrors = libxml_use_internal_errors(true);
        $loaded = $document->loadXML($documentXml, LIBXML_NONET | LIBXML_COMPACT);
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrors);

        if (! $loaded) {
            throw new RuntimeException('The CV document could not be read.');
        }

        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $body = $xpath->query('//w:body')->item(0);
        $content = '';

        if ($body) {
            foreach ($body->childNodes as $node) {
                if (! $node instanceof DOMElement) {
                    continue;
                }

                if ($node->localName === 'p') {
                    $content .= $this->paragraphHtml($xpath, $node);
                } elseif ($node->localName === 'tbl') {
                    $content .= $this->tableHtml($xpath, $node);
                }
            }
        }

        $title = e($media->getCustomProperty('title', $media->name));

        return '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
            .'<title>'.$title.'</title><style>'
            .'@page{margin:24mm}*{box-sizing:border-box}body{margin:0;background:#eef2f7;color:#243148;font:15px/1.65 Arial,sans-serif}'
            .'.sheet{width:min(900px,calc(100% - 32px));min-height:calc(100vh - 32px);margin:16px auto;padding:52px 58px;background:#fff;box-shadow:0 4px 24px rgba(31,45,61,.12)}'
            .'p{margin:0 0 9px;white-space:pre-wrap}h2{margin:20px 0 8px;padding-bottom:5px;color:#163a63;font-size:18px;border-bottom:1px solid #dce5ef}'
            .'table{width:100%;margin:10px 0 18px;border-collapse:collapse}td{padding:7px 9px;vertical-align:top;border:1px solid #dfe6ee}'
            .'@media(max-width:600px){.sheet{width:100%;margin:0;padding:28px 22px;box-shadow:none}}'
            .'</style></head><body><main class="sheet">'.$content.'</main></body></html>';
    }

    private function paragraphHtml(DOMXPath $xpath, DOMElement $paragraph): string
    {
        $parts = [];

        foreach ($xpath->query('.//w:t|.//w:tab|.//w:br', $paragraph) as $node) {
            $parts[] = match ($node->localName) {
                'tab' => '    ',
                'br' => "\n",
                default => $node->textContent,
            };
        }

        $text = trim(implode('', $parts));
        if ($text === '') {
            return '<p>&nbsp;</p>';
        }

        $style = strtolower((string) $xpath->evaluate('string(./w:pPr/w:pStyle/@w:val)', $paragraph));
        $isHeading = str_contains($style, 'heading') || str_contains($style, 'title');

        return $isHeading
            ? '<h2>'.e($text).'</h2>'
            : '<p>'.nl2br(e($text), false).'</p>';
    }

    private function tableHtml(DOMXPath $xpath, DOMElement $table): string
    {
        $html = '<table>';

        foreach ($xpath->query('./w:tr', $table) as $row) {
            $html .= '<tr>';
            foreach ($xpath->query('./w:tc', $row) as $cell) {
                $cellParts = [];
                foreach ($xpath->query('.//w:p', $cell) as $paragraph) {
                    $text = trim((string) $xpath->evaluate('string(.)', $paragraph));
                    if ($text !== '') {
                        $cellParts[] = e($text);
                    }
                }
                $html .= '<td>'.implode('<br>', $cellParts).'</td>';
            }
            $html .= '</tr>';
        }

        return $html.'</table>';
    }
}
