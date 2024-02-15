<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOtherExpenseRequest;
use App\Http\Resources\OtherExpenseCollection;
use App\Http\Resources\ShowOtherExpenseResource;
use App\Models\OtherExpense;
use Illuminate\Http\Request;

class OtherExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $otherExpenses = OtherExpense::orderBy('date')->paginate(10);
        return new OtherExpenseCollection($otherExpenses);
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOtherExpenseRequest $request)
    {
        return new ShowOtherExpenseResource(OtherExpense::create($request->all()));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
