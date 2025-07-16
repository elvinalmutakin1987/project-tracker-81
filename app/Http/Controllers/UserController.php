<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::where(function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
            $query->orWhere('username', 'like', '%' . $request->search . '%');
        })->where('username', '!=', 'superadmin');
        $show = 10;
        if ($request->show && $request->show != '5') {
            $show = $request->show;
        }
        $user = $user->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = Role::where('name', '!=', 'superadmin')->get();
        return view('user.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = [
            'username' => 'required|unique:users,username',
            'name' => 'required',
            'password' => 'required|string|max:255|confirmed',
        ];
        $request->validate($validate);
        DB::beginTransaction();
        try {
            $data = array_merge($request->except('_token', '_method', 'role_id'), [
                'password' => bcrypt($request->password),
                'email' => fake()->unique()->safeEmail(),
            ]);
            $user = User::create($data);
            $role = Role::find($request->role_id);
            $user->assignRole($role);
            DB::commit();
            return redirect()->route('user.index')->with([
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
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $role = Role::where('name', '!=', 'superadmin')->get();
        return view('user.edit', compact('user', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($request->password) {
            $validate = [
                'username' => 'required|unique:users,username,' . $user->id . ',id',
                'name' => 'required',
                'password' => 'required|string|max:255|confirmed',
            ];
        } else {
            $validate = [
                'username' => 'required|unique:users,username,' . $user->id . ',id',
                'name' => 'required',
            ];
        }
        $request->validate($validate);
        DB::beginTransaction();
        try {
            if ($request->password) {
                $data = array_merge($request->except('_token', '_method', 'role_id'), [
                    'password' => bcrypt($request->password)
                ]);
            } else {
                $data = array_merge($request->except('_token', '_method', 'role_id', 'password'));
            }
            $user->update($data);
            $role = Role::find($request->role_id);
            $user->syncRoles($role);
            DB::commit();
            return redirect()->route('user.index')->with([
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
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            return redirect()->route('user.index')->with([
                'status' => 'success',
                'message' => 'Data has been deleted!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }
}
