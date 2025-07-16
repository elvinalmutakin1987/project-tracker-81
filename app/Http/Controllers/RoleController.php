<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role = Role::where('name', 'like', '%' . $request->search . '%')
            ->where('name', '!=', 'superadmin');
        $show = 10;
        if ($request->show && $request->show != '5') {
            $show = $request->show;
        }
        $role = $role->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('role.index', compact('role'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = [
            'name' => 'required|unique:users,name',
        ];
        $request->validate($validate);
        DB::beginTransaction();
        try {
            $data = array_merge($request->except('_token', '_method'));
            $role = Role::create($data);
            if ($request->task_board_pre_sales) $role->givePermissionTo($request->task_board_pre_sales);
            if ($request->task_board_sales_admin) $role->givePermissionTo($request->task_board_sales_admin);
            if ($request->task_board_operation) $role->givePermissionTo($request->task_board_operation);
            if ($request->task_board_finance_accounting) $role->givePermissionTo($request->task_board_finance_accounting);
            if ($request->project) $role->givePermissionTo($request->project);
            if ($request->work_order) $role->givePermissionTo($request->work_order);
            if ($request->assignment) $role->givePermissionTo($request->assignment);
            if ($request->invoice) $role->givePermissionTo($request->invoice);
            if ($request->tool_kit_loan) $role->givePermissionTo($request->tool_kit_loan);
            if ($request->tool_kit_return) $role->givePermissionTo($request->tool_kit_return);
            if ($request->setting_brand) $role->givePermissionTo($request->setting_brand);
            if ($request->setting_customer) $role->givePermissionTo($request->setting_customer);
            if ($request->setting_customer_partnership) $role->givePermissionTo($request->setting_customer_partnership);
            if ($request->setting_work_type) $role->givePermissionTo($request->setting_work_type);
            if ($request->setting_role) $role->givePermissionTo($request->setting_role);
            if ($request->setting_user) $role->givePermissionTo($request->setting_user);
            if ($request->report) $role->givePermissionTo($request->report);
            if (
                $request->task_board_pre_sales
                || $request->task_board_sales_admin
                || $request->task_board_operation
                || $request->task_board_finance_accounting
            ) $role->givePermissionTo("task_board");
            if (
                $request->setting_brand
                || $request->setting_customer
                || $request->setting_customer_partnership
                || $request->setting_work_type
                || $request->setting_role
                || $request->setting_user
            ) $role->givePermissionTo("setting");
            if (
                $request->tool_kit_loan
                || $request->tool_kit_return
                || $request->tool_kit_stock
            ) $role->givePermissionTo("tool_kit");
            DB::commit();
            return redirect()->route('role.index')->with([
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
    public function show(Role $role)
    {
        return view('role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validate = [
            'name' => 'required|unique:users,name,' . $role->id . ',id',
        ];
        $request->validate($validate);
        DB::beginTransaction();
        try {
            $data = array_merge($request->except('_token', '_method'));
            $role->update($data);
            $role->syncPermissions();
            if ($request->task_board_pre_sales) $role->givePermissionTo($request->task_board_pre_sales);
            if ($request->task_board_sales_admin) $role->givePermissionTo($request->task_board_sales_admin);
            if ($request->task_board_operation) $role->givePermissionTo($request->task_board_operation);
            if ($request->task_board_finance_accounting) $role->givePermissionTo($request->task_board_finance_accounting);
            if ($request->project) $role->givePermissionTo($request->project);
            if ($request->work_order) $role->givePermissionTo($request->work_order);
            if ($request->assignment) $role->givePermissionTo($request->assignment);
            if ($request->invoice) $role->givePermissionTo($request->invoice);
            if ($request->tool_kit_loan) $role->givePermissionTo($request->tool_kit_loan);
            if ($request->tool_kit_return) $role->givePermissionTo($request->tool_kit_return);
            if ($request->setting_brand) $role->givePermissionTo($request->setting_brand);
            if ($request->setting_customer) $role->givePermissionTo($request->setting_customer);
            if ($request->setting_customer_partnership) $role->givePermissionTo($request->setting_customer_partnership);
            if ($request->setting_work_type) $role->givePermissionTo($request->setting_work_type);
            if ($request->setting_role) $role->givePermissionTo($request->setting_role);
            if ($request->setting_user) $role->givePermissionTo($request->setting_user);
            if ($request->report) $role->givePermissionTo($request->report);
            if (
                $request->task_board_pre_sales
                || $request->task_board_sales_admin
                || $request->task_board_operation
                || $request->task_board_finance_accounting
            ) $role->givePermissionTo("task_board");
            if (
                $request->setting_brand
                || $request->setting_customer
                || $request->setting_customer_partnership
                || $request->setting_work_type
                || $request->setting_role
                || $request->setting_user
            ) $role->givePermissionTo("setting");
            if (
                $request->tool_kit_loan
                || $request->tool_kit_return
                || $request->tool_kit_stock
            ) $role->givePermissionTo("tool_kit");
            DB::commit();
            return redirect()->route('role.index')->with([
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
    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {
            $role->delete();
            DB::commit();
            return redirect()->route('role.index')->with([
                'status' => 'success',
                'message' => 'Data has been deleted!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }
}
