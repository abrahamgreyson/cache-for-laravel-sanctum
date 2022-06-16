<?php

namespace CacheForLaravelSanctum;

use Cache;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\PersonalAccessToken as Sanctum;
use Log;

class PersonalAccessToken extends Sanctum
{

    public static int $ttl = 600;

    /**
     * Find the token instance matching the given token.
     *
     * @param string $token
     * @return static|null
     */
    public static function findToken($token)
    {
        $token = Cache::remember("personal-access-token::$token", self::$ttl, function () use ($token) {
            return parent::findToken($token) ?? '_null_';
        });
        if ($token === '_null_') {
            return null;
        }

        return $token;
    }

    /**
     * Get the tokenable model that the access token belongs to.
     *
     * @return Attribute
     */
    public function tokenable(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Cache::remember("personal-access-token::{$attributes['id']}::tokenable", self::$ttl, function () {
                return parent::tokenable()->first();
            })
        );
    }

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::updating(function (self $personalAccessToken) {
            try {
                Cache::remember("personal-access-token::{$personalAccessToken->id}::last-used-update", 3600, function () use ($personalAccessToken) {
                    SanctumTokenUsed::dispatch($personalAccessToken, $personalAccessToken->getDirty());
                    return now();
                });
            } catch (\Exception $e) {
                Log::critical($e->getMessage());
            }
            return false;
        });
    }

}
