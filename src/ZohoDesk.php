<?php

namespace Marshmallow\ZohoDesk;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Marshmallow\ZohoDesk\Models\ZohoToken;

class ZohoDesk
{
    public $access_token;

    public function get(string $endpoint): Collection
    {
        try {
            $desk = new self();
            $response = Http::withToken(
                $desk->getAccessToken()
            )->get(config('zohodesk.desk_host').$endpoint);

            if ($response->successful()) {
                if (isset($response->json()['data'])) {
                    return collect($response->json()['data']);
                }
                if (!$response->json()) {
                    return collect([]);
                }
            }

            $error = $response->json();
            throw new Exception($error['errorCode'].': '.$error['message']);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function post(string $endpoint, array $data): array
    {
        try {
            $desk = new self();
            $response = Http::withToken(
                $desk->getAccessToken()
            )->post(config('zohodesk.desk_host').$endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            $error = $response->json();
            throw new Exception($error['errorCode'].': '.$error['message']);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function patch(string $endpoint, array $data): array
    {
        try {
            $desk = new self();
            $response = Http::withToken(
                $desk->getAccessToken()
            )->patch(config('zohodesk.desk_host').$endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            $error = $response->json();
            throw new Exception($error['errorCode'].': '.$error['message']);
        } catch (Exception $e) {
            dd($e->getMessage());
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
        $client_id = $config['client_id'];
        $client_secret = $config['client_secret'];
        $code = $config['code'];
        $redirect_uri = 'XXXXXXXX';

        $config = array_merge([
            'grant_type' => 'authorization_code',
        ], $config);

        $response = Http::post(config('zohodesk.auth_host').'/token?'.http_build_query($config));

        if (array_key_exists('error', $response->json())) {
            throw new Exception($response->json()['error']);
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
            'client_id' => config('zoho.client_id'),
            'client_secret' => config('zoho.client_secret'),
            'scope' => join(',', config('zoho.scopes')),
            // 'redirect_uri' => 'XXXXXXXX',
            'grant_type' => 'refresh_token',
        ];

        $response = Http::post(config('zohodesk.auth_host').'/token?'.http_build_query($config));
        $token->update(
            $response->json()
        );

        return $token;
    }
}
