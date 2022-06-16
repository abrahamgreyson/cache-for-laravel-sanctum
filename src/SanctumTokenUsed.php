<?php

namespace CacheForLaravelSanctum;

use Illuminate\Foundation\Events\Dispatchable;

class SanctumTokenUsed
{
    use Dispatchable;

    private PersonalAccessToken $personalAccessToken;
    private array $data;

    public function __construct(PersonalAccessToken $personalAccessToken, array $data)
    {
        $this->personalAccessToken = $personalAccessToken;
        $this->data = $data;
    }

    // stub
    public function handle($event)
    {
        \DB::table($this->personalAccessToken->getTable())
            ->where('id', $this->personalAccessToken->id)
            ->update($this->data);
    }
}
