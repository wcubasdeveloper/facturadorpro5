<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Tenant\User;

class TenantAddPermissionEditCpeToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('permission_edit_cpe')->default(false)->after('type');
        });
        
        $user = User::first();

        if($user){
            $user->update(['permission_edit_cpe' => true]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('permission_edit_cpe');
        });
    }
}
