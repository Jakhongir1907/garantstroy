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
        $currentDate = now();

        // Calculate the start date (6 months ago)
        $startDate = $currentDate->copy()->subMonths(5)->startOfMonth();

        // Calculate the end date (current month)
        $endDate = $currentDate->endOfMonth();

        // Query to get total incomes for each month
        $incomes = Income::select(DB::raw('YEAR(date) as year, MONTH(date) as month, SUM(amount) as total_income'))
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(date), MONTH(date)'))
            ->get();

        // Query to get total expenses for each month
        $expenses = Expense::select(DB::raw('YEAR(date) as year, MONTH(date) as month, SUM(amount) as total_expense'))
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy(DB::raw('YEAR(date), MONTH(date)'))
            ->get();

        // Calculate profits for each month
        $profits = [];
        foreach ($incomes as $income) {
            $profits[$income->year][$income->month] = $income->total_income;
        }

        foreach ($expenses as $expense) {
            if (!isset($profits[$expense->year][$expense->month])) {
                $profits[$expense->year][$expense->month] = 0;
            }
            $profits[$expense->year][$expense->month] -= $expense->total_expense;
        }
        return response()->json([
            'months' => $months ,
            'profits' => $profits
        ]);
    }
}
