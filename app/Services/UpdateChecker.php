<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateChecker
{
    private string $url;

    public function __construct()
    {
        $this->url = config('updater.url');
    }

    public function getCurrentVersion(): string
    {
        $versionFile = base_path('version.txt');

        if (! file_exists($versionFile)) {
            return '0.0.0';
        }

        return trim(file_get_contents($versionFile));
    }

    private function fetchAndCacheApiResponse(): ?array
    {
        try {

            return cache()->remember(
                'api_response',
                now()->addHours(8),
                function () {

                    try {

                        $response = Http::timeout(30)
                            ->connectTimeout(15)
                            ->withHeaders([
                                'User-Agent' => 'Lakasir',
                                'Accept' => 'application/vnd.github+json',
                            ])
                            ->get($this->url);

                        if (! $response->successful()) {
                            return null;
                        }

                        return $response->json();

                    } catch (\Throwable $e) {

                        Log::warning(
                            'Update checker request failed',
                            [
                                'message' => $e->getMessage(),
                            ]
                        );

                        return null;
                    }
                }
            );

        } catch (\Throwable $e) {

            Log::warning(
                'Update checker cache failed',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return null;
        }
    }

    public function getLatestVersion(): ?string
    {
        $response = $this->fetchAndCacheApiResponse();

        if (! $response) {
            return null;
        }

        return ltrim($response['tag_name'] ?? '', 'v');
    }

    public function isUpdateAvailable(): bool
    {
        try {

            $current = $this->getCurrentVersion();
            $latest = $this->getLatestVersion();

            if (! $latest) {
                return false;
            }

            return version_compare(
                $latest,
                $current,
                '>'
            );

        } catch (\Throwable $e) {

            return false;
        }
    }

    public function getChangelog(): ?string
    {
        $response = $this->fetchAndCacheApiResponse();

        return $response['body'] ?? null;
    }

    public function getChangelogLines(): array
    {
        $body = $this->getChangelog();

        if (! $body) {
            return [];
        }

        return array_filter(
            preg_split('/\r\n|\r|\n/', $body)
        );
    }
}