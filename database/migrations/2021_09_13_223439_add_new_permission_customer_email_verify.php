<?php

use Konekt\Acl\Models\Role;
use Konekt\Acl\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class AddNewPermissionCustomerEmailVerify extends Migration
{
    private $permissions = [
        'list support',
        'access customer email verify',
        'modify customer email verify'
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $createdPermissions = [];
        foreach ($this->permissions as $permission) {
            $createdPermissions[] = Permission::create(['name' => $permission]);
        }

        $adminRole = Role::where(['name' => 'admin'])->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($createdPermissions);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $adminRole = Role::where(['name' => 'admin'])->first();

        foreach ($this->permissions as $permission) {
            if ($adminRole) {
                $adminRole->revokePermissionTo($permission);
            }
            Permission::delete(['name' => $permission]);
        }
    }
}
