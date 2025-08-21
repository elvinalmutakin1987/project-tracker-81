<?php

namespace App\Http\Controllers;

use App\Models\Work_order;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $work_order = Work_order::where('wo_number', 'like', '%' . $request->search . '%');
        $show = 10;
        if ($request->show && $request->show != '5') {
            $show = $request->show;
        }
        $work_order = $work_order->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('work_order.index', compact('work_order'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Work_order $work_order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Work_order $work_order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Work_order $work_order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Work_order $work_order)
    {
        //
    }
}
