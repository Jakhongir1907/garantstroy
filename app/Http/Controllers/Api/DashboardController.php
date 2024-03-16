<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $profits = [];

        // Get the current date
        $currentDate = now();

        // Calculate the start date (6 months ago)
        $startDate = $currentDate->copy()->subMonths(5)->startOfMonth();

        // Calculate the end date (current month)
        $endDate = $currentDate->endOfMonth();

        // Loop through the last six months
        for ($i = 0; $i < 6; $i++) {
            // Calculate the month and year for the current iteration
            $month = $startDate->copy()->addMonths($i)->month;
            $year = $startDate->copy()->addMonths($i)->year;

            // Query to get total income for the current month
            $totalIncome = Income::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('amount');

            // Query to get total expenses for the current month
            $totalExpense = Expense::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('amount');

            // Calculate the profit for the current month
            $profit = $totalIncome - $totalExpense;

            // Add the profit to the profits array
            $profits[] = $profit;
        }

        // Fill missing months with 0 profit
        $missingMonthsCount = 6 - count($profits);
        for ($i = 0; $i < $missingMonthsCount; $i++) {
            $profits[] = 0;
        }

        return response()->json([
            'months' => $months ,
            'profits' => $profits
        ]);
    }
}
