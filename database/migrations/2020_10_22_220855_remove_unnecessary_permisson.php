<?php

use Konekt\Acl\Models\Role;
use Konekt\Acl\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnnecessaryPermisson extends Migration
{
    private $permissions = [
        'list addresses',
        'create addresses',
        'view addresses',
        'edit addresses',
        'delete addresses',
        'list products',
        'create products',
        'view products',
        'edit products',
        'delete products',
        'list orders',
        'create orders',
        'view orders',
        'edit orders',
        'delete orders',
        'list media',
        'create media',
        'view media',
        'edit media',
        'delete media',
        'list taxonomies',
        'create taxonomies',
        'view taxonomies',
        'edit taxonomies',
        'delete taxonomies',
        'list taxons',
        'create taxons',
        'view taxons',
        'edit taxons',
        'delete taxons',
        'list properties',
        'create properties',
        'view properties',
        'edit properties',
        'delete properties',
        'list propertyvalues',
        'create propertyvalues',
        'view propertyvalues',
        'edit propertyvalues',
        'delete propertyvalues',
        'list channels',
        'create channels',
        'view channels',
        'edit channels',
        'delete channels',
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $adminRole = Role::where(['name' => 'admin'])->first();

        foreach ($this->permissions as $permission) {
            if ($adminRole) {
                $adminRole->revokePermissionTo($permission);
            }
            Permission::where('name', $permission)->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
