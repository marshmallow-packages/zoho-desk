<?php

namespace Marshmallow\ZohoDesk;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Marshmallow\ZohoDesk\Exceptions\ZohoAuthException;
use Marshmallow\ZohoDesk\Exceptions\ZohoBadRequestException;
use Marshmallow\ZohoDesk\Exceptions\ZohoGetException;
use Marshmallow\ZohoDesk\Exceptions\ZohoPathException;
use Marshmallow\ZohoDesk\Exceptions\ZohoPostException;
use Marshmallow\ZohoDesk\Exceptions\ZohoRefreshAccessTokenException;
use Marshmallow\ZohoDesk\Models\ZohoToken;

class ZohoDesk
{
    public $access_token;

    protected $attachment = [];

    protected $host = 'desk_host';

    public static $zohoTokenModel = ZohoToken::class;

    public function get(string $endpoint)
    {
        try {
            $desk = new self;
            $response = Http::withToken(
                $desk->getAccessToken()
            )->get($this->buildApiPath($endpoint));

            if ($response->successful()) {
                if (isset($response->json()['data'])) {
                    return collect($response->json()['data']);
                }
                if (! empty($response->json())) {
                    return (object) $response->json();
                }
                if (! $response->json()) {
                    return collect([]);
                }
            }

            throw new Exception($this->formatError($response->json(), $endpoint));
        } catch (Exception $e) {
            throw new ZohoGetException($e->getMessage(), $e->getCode());
        }
    }

    public function attach(string $relative_path, string $field_name = 'file')
    {
        $this->attachment[] = [
            $field_name => $relative_path,
        ];

        return $this;
    }

    public function post(string $endpoint, array $data = []): array
    {
        try {
            $desk = new self;
            $client = Http::withToken(
                $desk->getAccessToken()
            );

            if (! empty($this->attachment)) {
                foreach ($this->attachment as $attachment) {
                    foreach ($attachment as $field_name => $relative_path) {
                        $photo = fopen(storage_path("{$relative_path}"), 'r');
                        $client->attach($field_name, $photo, $relative_path);
                    }
                }
            }

            $response = $client->post($this->buildApiPath($endpoint), $data);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception($this->formatError($response->json(), $endpoint, $data));
        } catch (Exception $e) {
            if (Str::contains($e->getMessage(), 'BAD_REQUEST')) {
                throw new ZohoBadRequestException($e->getMessage(), $e->getCode());
            }
            throw new ZohoPostException($e->getMessage(), $e->getCode());
        }
    }

    public function patch(string $endpoint, array $data): array
    {
        try {
            $desk = new self;
            $response = Http::withToken(
                $desk->getAccessToken()
            )->patch($this->buildApiPath($endpoint), $data);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception($this->formatError($response->json(), $endpoint, $data));
        } catch (Exception $e) {
            throw new ZohoPathException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Build a debuggable exception message from a failed Zoho response.
     *
     * Includes Zoho's `errors` array (which names the offending field on
     * INVALID_DATA), the endpoint, and — for write requests — the payload.
     *
     * @param  array<string, mixed>|null  $error
     * @param  array<string, mixed>|null  $data
     */
    protected function formatError(?array $error, string $endpoint, ?array $data = null): string
    {
        $error = $error ?? [];

        $message = sprintf(
            '%s: %s - %s - %s',
            $error['errorCode'] ?? 'UNKNOWN',
            $error['message'] ?? 'No message provided by Zoho',
            json_encode($error['errors'] ?? []),
            $endpoint
        );

        if ($data !== null) {
            $message .= ' - '.json_encode($data);
        }

        return $message;
    }

    protected function buildApiPath(string $endpoint): string
    {
        $host = config("zohodesk.{$this->host}");

        return $host.$endpoint;
    }

    protected function portal(): self
    {
        $this->host = 'desk_portal_host';

        return $this;
    }

    protected function getAccessToken()
    {
        $token = self::$zohoTokenModel::firstOrFail();
        if ($token->isExpired()) {
            $token->refresh();
        }

        return $token->access_token;
    }

    public function dateFormat(Carbon $date)
    {
        return $date->timezone('UTC')->format('Y-m-d\TH:i:s\.000\Z');
    }

    public function auth(array $config): self
    {
        $config = array_merge([
            'grant_type' => 'authorization_code',
        ], $config);

        $response = Http::post(config('zohodesk.auth_host').'/token?'.http_build_query($config));

        if (array_key_exists('error', $response->json())) {
            throw new ZohoAuthException($response->json()['error'], 1);
        }

        if ($token = self::$zohoTokenModel::first()) {
            $token->delete();
        }

        self::$zohoTokenModel::create($response->json());

        return new self;
    }

    public function refreshAccessToken(ZohoToken $token): ZohoToken
    {
        $config = [
            'refresh_token' => $token->refresh_token,
            'client_id' => config('zohodesk.client_id'),
            'client_secret' => config('zohodesk.client_secret'),
            'scope' => implode(',', config('zohodesk.scopes')),
            // 'redirect_uri' => 'XXXXXXXX',
            'grant_type' => 'refresh_token',
        ];

        $response = Http::post(config('zohodesk.auth_host').'/token?'.http_build_query($config));

        if (array_key_exists('error', $response->json())) {
            throw new ZohoRefreshAccessTokenException($response->json()['error'], 1);
        }

        $token->update(
            collect($response->json())
                ->only([
                    'access_token', 'api_domain', 'token_type', 'expires_in',
                ])
                ->toArray()
        );

        return $token;
    }

    public function active()
    {
        return config('zohodesk.active');
    }

    public function notActive()
    {
        return ! $this->active();
    }
}
