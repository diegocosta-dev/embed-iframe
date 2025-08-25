<?php

namespace diegocosta\craftembediframe\models;

use craft\base\Model;

class Settings extends Model
{
    public string $navLabel = 'Embed Iframe';
    public string $embedUrl = 'https://example.com';
    public bool $sandboxSameOrigin = false;

    /**
    * List of allowed hosts (optional).
    * Can be overridden in config/_embed-iframe.php:
    * return ['allowedHosts' => ['example.com','*.youtube.com']];
    */

    public array $allowedHosts = [];

    public function rules(): array
    {
        return [
            [['navLabel', 'embedUrl'], 'trim'],
            [['navLabel', 'embedUrl'], 'required'],
            [['navLabel', 'embedUrl'], 'string'],

            // force valid URL and https by default
            ['embedUrl', 'url', 'defaultScheme' => 'https'],

            // prohibits dangerous schemes
            ['embedUrl', function ($attr) {
                $url = trim((string)$this->$attr);
                if (!$url) return;
                $scheme = parse_url($url, PHP_URL_SCHEME);
                if (!$scheme || !in_array(strtolower($scheme), ['http','https'], true)) {
                    $this->addError($attr, 'Only http/https URLs are allowed.');
                }
            }],

            ['sandboxSameOrigin', 'boolean'],

            // validate host against allowlist (if defined)
            ['embedUrl', function ($attr) {
                if (!$this->allowedHosts) return;
                $host = parse_url((string)$this->$attr, PHP_URL_HOST) ?: '';
                $host = strtolower($host);

                $ok = false;
                foreach ($this->allowedHosts as $pattern) {
                    $pattern = strtolower($pattern);
                    if (str_starts_with($pattern, '*.' )) {
                        // wildcard *.example.com
                        $base = substr($pattern, 2);
                        if ($host === $base || str_ends_with($host, '.'.$base)) {
                            $ok = true; break;
                        }
                    } else {
                        if ($host === $pattern) { $ok = true; break; }
                    }
                }
                if (!$ok) {
                    $this->addError($attr, 'Host is not in the allowed list.');
                }
            }],
        ];
    }
}
