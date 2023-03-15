<?php

use Konekt\Acl\Models\Role;
use Konekt\Acl\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewPermissionRoleForMarketingAndMarketplace extends Migration
{
    private $permissions = [
        'list marketplaces',
        'create marketplaces',
        'view marketplaces',
        'edit marketplaces',
        'delete marketplaces',
        'list marketings',
        'create marketings',
        'view marketings',
        'edit marketings',
        'delete marketings',
    ];

    private $dropPermissions = [
        'list testimonials',
        'list home pages',
        'list professional valuations',
        'list faq categories',
        'list internal adverts',
        'list case studies',
        'list sell with uses'
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

        foreach ($this->dropPermissions as $dropPermission) {
            if ($adminRole) {
                $adminRole->revokePermissionTo($dropPermission);
            }
            Permission::where('name', $dropPermission)->delete();
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
