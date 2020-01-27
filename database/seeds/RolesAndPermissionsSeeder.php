<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'reply tickets']);

        $role = Role::create(['name' => 'TICKETS_ADMIN'])
            ->givePermissionTo(['reply tickets']);

        $role = Role::create(['name' => 'USERS_ADMIN'])
            ->givePermissionTo(['edit users']);

        $admin1 = new User;
        $admin1->fullname = 'Admin 1 (for users)';
        $admin1->email = 'user@admin.com';
        $admin1->password = app('hash')->make('123');
        $admin1->save();
        $admin1->assignRole('USERS_ADMIN');

        $admin1 = new User;
        $admin1->fullname = 'Admin 2 (for tickets)';
        $admin1->email = 'ticket@admin.com';
        $admin1->password = app('hash')->make('123');
        $admin1->save();
        $admin1->assignRole('TICKETS_ADMIN');
    }
}
