<?php

namespace Marshmallow\ZohoDesk;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Marshmallow\ZohoDesk\Models\ZohoToken;
use Marshmallow\ZohoDesk\Exceptions\ZohoGetException;
use Marshmallow\ZohoDesk\Exceptions\ZohoAuthException;
use Marshmallow\ZohoDesk\Exceptions\ZohoPathException;
use Marshmallow\ZohoDesk\Exceptions\ZohoPostException;
use Marshmallow\ZohoDesk\Exceptions\ZohoBadRequestException;
use Marshmallow\ZohoDesk\Exceptions\ZohoRefreshAccessTokenException;

class ZohoDesk
{
    public $access_token;

    protected $attachment = [];

    public function get(string $endpoint)
    {
        try {
            $desk = new self();
            $response = Http::withToken(
                $desk->getAccessToken()
            )->get(config('zohodesk.desk_host') . $endpoint);

            if ($response->successful()) {
                if (isset($response->json()['data'])) {
                    return collect($response->json()['data']);
                }
                if (!empty($response->json())) {
                    return (object) $response->json();
                }
                if (!$response->json()) {
                    return collect([]);
                }
            }

            $error = $response->json();
            throw new Exception($error['errorCode'] . ': ' . $error['message']);
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
            $desk = new self();
            $client = Http::withToken(
                $desk->getAccessToken()
            );

            if (!empty($this->attachment)) {
                foreach ($this->attachment as $attachment) {
                    foreach ($attachment as $field_name => $relative_path) {
                        $photo = fopen(storage_path("{$relative_path}"), 'r');
                        $client->attach($field_name, $photo, $relative_path);
                    }
                }
            }

            $response = $client->post(config('zohodesk.desk_host') . $endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            $error = $response->json();
            throw new Exception($error['errorCode'] . ': ' . $error['message']);
        } catch (Exception $e) {
            if (Str::contains($e->getMessage(), 'BAD_REQUEST')) {
                $message = $e->getMessage() . ' - ' . $endpoint . ' - ' . json_encode($data);
                throw new ZohoBadRequestException($message, $e->getCode());
            }
            throw new ZohoPostException($e->getMessage(), $e->getCode());
        }
    }

    public function patch(string $endpoint, array $data): array
    {
        try {
            $desk = new self();
            $response = Http::withToken(
                $desk->getAccessToken()
            )->patch(config('zohodesk.desk_host') . $endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            $error = $response->json();
            throw new Exception($error['errorCode'] . ': ' . $error['message']);
        } catch (Exception $e) {
            throw new ZohoPathException($e->getMessage(), $e->getCode());
        }
    }

    protected function getAccessToken()
    {
        $token = ZohoToken::firstOrFail();
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

        $response = Http::post(config('zohodesk.auth_host') . '/token?' . http_build_query($config));

        if (array_key_exists('error', $response->json())) {
            throw new ZohoAuthException($response->json()['error'], 1);
        }

        if ($token = ZohoToken::first()) {
            $token->delete();
        }

        ZohoToken::create($response->json());

        return new self();
    }

    public function refreshAccessToken(ZohoToken $token): ZohoToken
    {
        $config = [
            'refresh_token' => $token->refresh_token,
            'client_id' => config('zohodesk.client_id'),
            'client_secret' => config('zohodesk.client_secret'),
            'scope' => join(',', config('zohodesk.scopes')),
            // 'redirect_uri' => 'XXXXXXXX',
            'grant_type' => 'refresh_token',
        ];

        $response = Http::post(config('zohodesk.auth_host') . '/token?' . http_build_query($config));

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
        return (!$this->active());
    }
}
