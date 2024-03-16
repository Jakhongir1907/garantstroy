<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Project;
use App\Models\Worker;
use App\Models\WorkerAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function allData(){

       // Foyda uchun
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
       // Total amount of Incomes for this Year
        // Get the current year
        $currentYear = date('Y');
        // Query to get the total amount of all incomes for the current year
        $totalIncomeAmount = Income::whereYear('date', $currentYear)->sum('amount');

        // Total amount of Expenses for this Year
        $totalExpenseAmount = Expense::whereYear('date' , $currentYear)->sum('amount');

        //  Workers
        $workers = Worker::all();
        $allWorkers = 0;
        $activeWorkers = 0;
        foreach ($workers as $worker){
            $allWorkers ++;
            $workerAccount = WorkerAccount::where('worker_id' , $worker->id)->where('status' ,'working')->latest()->first();
            if($workerAccount){
             $activeWorkers ++;
            }
        }
        // Projects number
        $activeProjects = Project::where('state' , 'active')->count();
        $allProjects = Project::count();

        return response()->json([
            'months' => $months ,
            'profits' => $profits ,
            'total_incomes_amount' => $totalIncomeAmount ,
            'total_expenses_amount' => $totalExpenseAmount ,
            'all_workers_number' => $allWorkers ,
            'active_workers_number' => $activeWorkers ,
            'all_projects' => $allProjects,
            'active_projects' => $activeProjects
        ]);
    }
}
