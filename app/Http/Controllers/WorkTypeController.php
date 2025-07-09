<?php

namespace App\Http\Controllers;

use App\Models\Work_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $work_type = Work_type::where('work_name', 'like', '%' . $request->search . '%');
        $show = 10;
        if ($request->show && $request->show != '5') {
            $show = $request->show;
        }
        $work_type = $work_type->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('work_type.index', compact('work_type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('work_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = [
            'work_name' => 'required|unique:work_types,work_name',
        ];
        $request->validate($validate);
        DB::beginTransaction();
        try {
            $data = array_merge($request->except('_token', '_method'));
            $work_type = Work_type::create($data);
            DB::commit();
            return redirect()->route('work_type.index')->with([
                'status' => 'success',
                'message' => 'Data has been saved!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Work_type $work_type)
    {
        return view('work_type.show', compact('work_type'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Work_type $work_type)
    {
        return view('work_type.edit', compact('work_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Work_type $work_type)
    {
        $validate = [
            'work_name' => 'required|unique:work_types,work_name,' . $work_type->id . ',id',
        ];
        $request->validate($validate);
        DB::beginTransaction();
        try {
            $data = array_merge($request->except('_token', '_method'));
            $work_type->update($data);
            DB::commit();
            return redirect()->route('work_type.index')->with([
                'status' => 'success',
                'message' => 'Data has been saved!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Work_type $work_type)
    {
        DB::beginTransaction();
        try {
            $work_type->delete();
            DB::commit();
            return redirect()->route('work_type.index')->with([
                'status' => 'success',
                'message' => 'Data has been deleted!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }
}
