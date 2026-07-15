<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\PollingUnit;
use Illuminate\Http\Request;

class GeographyController extends Controller
{
    public function lgas()
    {
        $lgas = Lga::all();
        return response()->json([
            'success' => true,
            'message' => 'LGAs retrieved successfully.',
            'data' => $lgas
        ]);
    }

    public function wards(Request $request)
    {
        $query = Ward::query();
        if ($request->has('lga_id')) {
            $query->where('lga_id', $request->lga_id);
        }
        $wards = $query->get();
        return response()->json([
            'success' => true,
            'message' => 'Wards retrieved successfully.',
            'data' => $wards
        ]);
    }

    public function pollingUnits(Request $request)
    {
        $query = PollingUnit::query();
        if ($request->has('ward_id')) {
            $query->where('ward_id', $request->ward_id);
        }
        $pollingUnits = $query->get();
        return response()->json([
            'success' => true,
            'message' => 'Polling units retrieved successfully.',
            'data' => $pollingUnits
        ]);
    }

    public function roles()
    {
        $roles = \Spatie\Permission\Models\Role::whereIn('name', [
            'Volunteer',
            'Supporter',
            'Canvaser',
            'Patron',
            'Financial Supporter'
        ])->get();

        return response()->json([
            'success' => true,
            'message' => 'Roles retrieved successfully.',
            'data' => $roles
        ]);
    }

    public function settings()
    {
        $quickActionsEnabled = \App\Models\Setting::get('quick_actions_enabled', '1') === '1';
        return response()->json([
            'success' => true,
            'message' => 'Settings retrieved successfully.',
            'data' => [
                'quick_actions_enabled' => $quickActionsEnabled,
                'quick_action_recruit_visible' => \App\Models\Setting::get('quick_action_recruit_visible', '1') === '1',
                'quick_action_results_visible' => \App\Models\Setting::get('quick_action_results_visible', '1') === '1',
                'quick_action_incident_visible' => \App\Models\Setting::get('quick_action_incident_visible', '1') === '1',
                'quick_action_voice_visible' => \App\Models\Setting::get('quick_action_voice_visible', '1') === '1',
                'quick_action_members_visible' => \App\Models\Setting::get('quick_action_members_visible', '1') === '1',
                'quick_action_mobilize_visible' => \App\Models\Setting::get('quick_action_mobilize_visible', '1') === '1',
            ]
        ]);
    }
}
