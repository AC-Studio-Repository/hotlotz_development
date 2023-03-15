<?php

use Konekt\Acl\Models\Role;
use Konekt\Acl\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropContentManagementRole extends Migration
{
    private $dropPermissions = [
        'delete content managements',
        'edit content managements',
        'create content managements'
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $adminRole = Role::where(['name' => 'admin'])->first();

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
        //
    }
}
