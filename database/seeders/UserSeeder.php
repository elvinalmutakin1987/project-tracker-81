<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'superadmin']);


        Permission::create(['name' => 'project']);

        Permission::create(['name' => 'task_board']);
        Permission::create(['name' => 'task_board.pre_sales']);
        Permission::create(['name' => 'task_board.sales_admin']);
        Permission::create(['name' => 'task_board.operation']);
        Permission::create(['name' => 'task_board.finance_accounting']);

        Permission::create(['name' => 'report']);

        Permission::create(['name' => 'setting']);
        Permission::create(['name' => 'setting.work_type']);
        Permission::create(['name' => 'setting.role']);
        Permission::create(['name' => 'setting.user']);

        $data = [
            "username" => "superadmin",
            "name" => "MBS Super Admin",
            "email" => "elvin@mbscctv.com",
            "password" => bcrypt("Mbs1234!"),
            "email_verified_at" => now(),
            "remember_token" =>  Str::random(10)
        ];

        DB::table('users')->insert($data);
        $user = User::find(1);
        $permission = Permission::all();
        $role = Role::find(1);
        $user->givePermissionTo($permission);
        $user->assignRole($role);
        $role->givePermissionTo($permission);
    }
}
