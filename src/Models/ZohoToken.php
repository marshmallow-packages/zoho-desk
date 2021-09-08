<?php

namespace Marshmallow\ZohoDesk\Models;

use Illuminate\Database\Eloquent\Model;
use Marshmallow\ZohoDesk\Facades\ZohoDesk;

class ZohoToken extends Model
{
    protected $guarded = [];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (ZohoToken $token) {
            $token->setExpiresAt(
                $token->expires_in
            );
        });

        static::updating(function (ZohoToken $token) {
            $token->setExpiresAt(
                $token->expires_in
            );
        });
    }

    /**
     * Calculate when this access token will be expired.
     *
     * @param int $expires_in_seconds how long the token will be valid
     */
    protected function setExpiresAt(int $expires_in_seconds): void
    {
        $this->expires_at = now()->addSeconds($expires_in_seconds);
    }

    /**
     * Check if the token is expired based on the expires_at
     * column in the ZohoToken model.
     *
     * @return bool True of False
     */
    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }

    /**
     * Get a new access token from Zoho.
     *
     * @return ZohoToken
     */
    public function refresh(): self
    {
        return ZohoDesk::refreshAccessToken($this);
    }
}
