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

    public function settings()
    {
        $quickActionsEnabled = \App\Models\Setting::get('quick_actions_enabled', '1') === '1';
        return response()->json([
            'success' => true,
            'message' => 'Settings retrieved successfully.',
            'data' => [
                'quick_actions_enabled' => $quickActionsEnabled
            ]
        ]);
    }
}
