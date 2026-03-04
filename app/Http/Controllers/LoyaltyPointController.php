<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPoint;
use Illuminate\Http\Request;

class LoyaltyPointController extends Controller
{
    public function index()
    {
        $points = auth()->user()->loyaltyPoints()
            ->latest()
            ->paginate(15);
            
        $totalPoints = auth()->user()->total_points;
        $pointsValue = auth()->user()->points_value;

        return view('loyalty-points.index', compact('points', 'totalPoints', 'pointsValue'));
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $points = $request->points;

        if (!$user->hasEnoughPoints($points)) {
            return back()->with('error', 'Poin tidak cukup!');
        }

        try {
            $user->redeemPoints($points, $request->description);
            
            return back()->with('success', "Berhasil menukarkan {$points} poin!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
