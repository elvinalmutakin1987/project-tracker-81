<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChangePasswordController extends Controller
{
    public function index(Request $request)
    {
        return view('change_password');
    }

    public function store(Request $request)
    {
        $validate = [
            'password' => 'required|string|max:255|confirmed',
        ];
        $request->validate($validate);
        DB::beginTransaction();
        try {
            $user = User::find(Auth::user()->id);
            $data = array_merge($request->except('_token', '_method'), [
                'password' => bcrypt($request->password)
            ]);
            $user->update($data);
            DB::commit();
            return redirect()->route('home')->with([
                'status' => 'success',
                'message' => 'Data has been saved!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }
}
