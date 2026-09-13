<?php

namespace App\Http\Middleware;

use Closure;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XSS
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowBlankTargets = $request->routeIs('posts.store', 'posts.update');
        $request->merge($this->sanitize($request->all(), $allowBlankTargets));

        return $next($request);
    }

    private function sanitize(array $input, bool $allowBlankTargets = false): array
    {
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $input[$key] = $this->sanitize($value, $allowBlankTargets);
                continue;
            }

            if (! is_string($value) || $this->isSensitiveField((string) $key)) {
                continue;
            }

            if (preg_match('/<[^>]+>|&lt;[^&]+&gt;/i', $value) === 1) {
                $config = HTMLPurifier_Config::createDefault();
                $config->set('Core.Encoding', 'UTF-8');
                if ($allowBlankTargets) {
                    $config->set('Attr.AllowedFrameTargets', ['_blank']);
                }
                $config->set('Cache.DefinitionImpl', null);
                $input[$key] = (new HTMLPurifier($config))->purify(
                    html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8')
                );
            }
        }

        return $input;
    }

    private function isSensitiveField(string $key): bool
    {
        return $key === '_token' || str_contains($key, 'password');
    }
}
