<?php

use Konekt\Acl\Models\Role;
use Konekt\Acl\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePermission extends Migration
{
    private $permissions = [
        'list items',
        'create items',
        'view items',
        'edit items',
        'delete items',
        'list categories',
        'create categories',
        'view categories',
        'edit categories',
        'delete categories',
        'list auctions',
        'create auctions',
        'view auctions',
        'edit auctions',
        'delete auctions',
        'list content managements',
        'create content managements',
        'view content managements',
        'edit content managements',
        'delete content managements',
        'list testimonials',
        'list home pages',
        'list professional valuations',
        'list faq categories',
        'list internal adverts',
        'list case studies',
        'list sell with uses',
        'list email templates',
        'list reports',
        'create reports',
        'list sys configs',
        'create sys configs',
        'view sys configs',
        'edit sys configs',
        'delete sys configs',
        'list email triggers',
        'list item lifecycle triggers',
        'list item duplicators',
        'list xeros'
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
