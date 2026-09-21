<?php

namespace App\Website\Services;

use App\Website\Models\City;
use Illuminate\Support\Collection;

class HomepageCities
{
    public function get(): Collection
    {
        $state = config('site.current.search_state');
        if (! $state) {
            return collect();
        }

        return City::query()
            ->join('states', 'cities.state_id', '=', 'states.id')
            ->where('states.name', $state)
            ->whereNotNull('cities.name')
            ->whereRaw("TRIM(cities.name) != ''")
            ->distinct()
            ->orderBy('cities.name')
            ->pluck('cities.name');
    }
}
