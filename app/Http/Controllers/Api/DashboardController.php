<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function allData(){
        $months = [];
        $currentDate = new \DateTime();
        for ($i = 1; $i <= 6; $i++) {
            $currentDate->modify('-1 month');
            $formattedDate = $currentDate->format('F Y');
            $months[] = $formattedDate;
        }

        return response()->json([
            'months' => $months
        ]);
    }
}
