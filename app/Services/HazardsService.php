<?php

namespace App\Services;

use App\Router\Request
use App\Models\Hazard;
use App\Models\Team;

class HazardsService
{
    public static function indexHazards(Team $team)
    {
        $hazards = array_map(function ($hazard) {
            return $hazard->getAttributes();
        }, Hazard::where(['team_id', '=', $team->id])->get());

        return $hazards;
    }

    public static function getHazard($hazardId)
    {
        $hazard = Hazard::find($hazardId);
        return $hazard ? $hazard->getAttributes() : null;
    }

    public static function removeHazard($hazardId)
    {
        $hazard = Hazard::find($hazardId);
        return $hazard ? $hazard->delete() : false;
    }

    public static function updateHazard($hazardId, $attributes)
    {
        return $hazardId ? Hazard::find($hazardId)->update($attributes) : false;
    }

    public static function addHazard(Request $request)
    {
        $ingredientName = $request->input('name');
        $ingredientDescription = $request->input('description');

        return Hazard::firstOrCreate('name', strtolower($ingredientName), ['name' => strtolower($ingredientName), 'description' => $ingredientDescription]);
    }
}
