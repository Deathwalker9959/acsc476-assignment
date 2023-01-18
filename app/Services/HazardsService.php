<?php

namespace App\Services;

use App\Router\Request;
use App\Models\Hazard;
use App\Models\Team;

class HazardsService
{
    public static function indexHazards($team)
    {
        $hazards = Hazard::where([['team_id', '=', $team->id]])->get();

        return $hazards;
    }

    public static function getHazard($hazardId)
    {
        $hazard = Hazard::find($hazardId);
        return $hazard ? $hazard->getAttributes() : null;
    }

    public static function removeHazard($hazardId, Team $team)
    {
        $hazard = Hazard::find($hazardId);
        return $hazard ? $hazard->delete() : false;
    }

    public static function updateHazard($hazardId, Team $team, $attributes)
    {
        return $hazardId ? Hazard::find($hazardId)->update($attributes) : false;
    }

    public static function addHazard(Request $request, Team $team)
    {
        $ingredientName = $request->input('name');
        $ingredientDescription = $request->input('description');

        return Hazard::firstOrCreate([
            ['name', '=', strtolower($ingredientName)],
            ['team_id', '=', $team->id],
        ], [
            'name' => strtolower($ingredientName),
            'team_id' => $team->id,
            'description' => $ingredientDescription
        ]);
    }
}
