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
        Role::create(['name' => 'pre-sales']);
        Role::create(['name' => 'sales-admin']);
        Role::create(['name' => 'accounting']);
        Role::create(['name' => 'operation']);

        Permission::create(['name' => 'project']);

        Permission::create(['name' => 'task_board']);
        Permission::create(['name' => 'task_board.pre_sales']);
        Permission::create(['name' => 'task_board.sales_admin']);
        Permission::create(['name' => 'task_board.operation']);
        Permission::create(['name' => 'task_board.finance_accounting']);

        Permission::create(['name' => 'work_order']);
        Permission::create(['name' => 'assignment']);
        Permission::create(['name' => 'invoice']);
        Permission::create(['name' => 'report']);

        Permission::create(['name' => 'tool_kit']);
        Permission::create(['name' => 'tool_kit.loan']);
        Permission::create(['name' => 'tool_kit.return']);
        Permission::create(['name' => 'tool_kit.stock']);

        Permission::create(['name' => 'setting']);
        Permission::create(['name' => 'setting.brand']);
        Permission::create(['name' => 'setting.customer']);
        Permission::create(['name' => 'setting.customer.partnership']);
        Permission::create(['name' => 'setting.work_type']);
        Permission::create(['name' => 'setting.role']);
        Permission::create(['name' => 'setting.user']);

        $data = [
            [
                "username" => "superadmin",
                "name" => "MBS Super Admin",
                "email" => "elvin@mbscctv.com",
                "password" => bcrypt("Mbs1234!"),
                "email_verified_at" => now(),
                "remember_token" =>  Str::random(10)
            ],
            [
                "username" => "pre-sales",
                "name" => "Pre Sales",
                "email" => "pre-sales@mbscctv.com",
                "password" => bcrypt("Mbs1234!"),
                "email_verified_at" => now(),
                "remember_token" =>  Str::random(10)
            ],
            [
                "username" => "sales-admin",
                "name" => "Sales Admin",
                "email" => "sales-admin@mbscctv.com",
                "password" => bcrypt("Mbs1234!"),
                "email_verified_at" => now(),
                "remember_token" =>  Str::random(10)
            ],
            [
                "username" => "accounting",
                "name" => "Accounting",
                "email" => "accounting@mbscctv.com",
                "password" => bcrypt("Mbs1234!"),
                "email_verified_at" => now(),
                "remember_token" =>  Str::random(10)
            ],
            [
                "username" => "operation",
                "name" => "Operation",
                "email" => "operation@mbscctv.com",
                "password" => bcrypt("Mbs1234!"),
                "email_verified_at" => now(),
                "remember_token" =>  Str::random(10)
            ]
        ];

        DB::table('users')->insert($data);
        $user = User::find(1);
        $presales = User::find(2);
        $salesadmin = User::find(3);
        $accounting = User::find(4);
        $operation = User::find(5);

        $permission = Permission::all();
        $role = Role::find(1);

        $role_presales = Role::find(2);
        $role_salesadmin = Role::find(3);
        $role_accounting = Role::find(4);
        $role_operation = Role::find(5);

        // $user->givePermissionTo($permission);

        $user->assignRole($role);
        $role->givePermissionTo($permission);

        $presales->assignRole($role_presales);
        $role_presales->givePermissionTo([
            'task_board',
            'task_board.pre_sales'
        ]);

        $salesadmin->assignRole($role_salesadmin);
        $role_salesadmin->givePermissionTo([
            'task_board',
            'task_board.sales_admin'
        ]);

        $operation->assignRole($role_operation);
        $role_operation->givePermissionTo([
            'task_board',
            'task_board.operation'
        ]);

        $accounting->assignRole($role_accounting);
        $role_accounting->givePermissionTo([
            'task_board',
            'task_board.finance_accounting'
        ]);
    }
}
