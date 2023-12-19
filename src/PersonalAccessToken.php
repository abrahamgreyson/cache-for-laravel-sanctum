<?php

namespace CacheForLaravelSanctum;

use Cache;
use DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\PersonalAccessToken as Sanctum;
use Log;

class PersonalAccessToken extends Sanctum
{
    /**
     * The seconds to cache the token for.
     *
     */
    public static int $ttl = 3600;

    /**
     * The interval to refresh the last_used field in database.
     *
     */
    public static int $interval = 3600;

    /**
     * Find the token instance matching the given token.
     *
     * @param string $token
     * @return static|null
     */
    public static function findToken($token): ?static
    {
        $id = explode('|', $token)[0];
        $cachedToken = Cache::remember(
            "personal-access-token:$id",
            config('sanctum.cache.ttl') ?? self::$ttl,
            function () use ($token) {
                return parent::findToken($token) ?? '_null_';
            }
        );

        if ($cachedToken === '_null_' || $cachedToken->token !== $token) {
            return null;
        }

        return $cachedToken;
    }

    /**
     * Get the tokenable model that the access token belongs to.
     *
     * @return Attribute
     *
     * help wanted: return type ain't compatible with base class
     *
     * @phpstan-ignore-next-line
     */
    public function tokenable(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Cache::remember(
                "personal-access-token:{$attributes['id']}:tokenable",
                config('sanctum.cache.ttl') ?? self::$ttl,
                function () {
                    return parent::tokenable()->first();
                }
            )
        );
    }

    /**
     * Bootstrap the model and its traits.
     *
     * todo update cache
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::updating(function (self $personalAccessToken) {
            $interval = config('sanctum.cache.update_last_used_at_interval') ?? self::$interval;

            try {
                Cache::remember(
                    "personal-access-token:{$personalAccessToken->id}:last_used_at",
                    $interval,
                    function () use ($personalAccessToken) {
                        DB::table($personalAccessToken->getTable())
                            ->where('id', $personalAccessToken->id)
                            ->update($personalAccessToken->getDirty());

                        return now();
                    }
                );
            } catch (\Exception $e) {
                Log::critical($e->getMessage());
            }

            return false;
        });

        static::deleting(function (self $personalAccessToken) {
            Cache::forget("personal-access-token:{$personalAccessToken->id}");
            Cache::forget("personal-access-token:{$personalAccessToken->id}:last_used_at");
            Cache::forget("personal-access-token:{$personalAccessToken->id}:tokenable");
        });
    }
}
