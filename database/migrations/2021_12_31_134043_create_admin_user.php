<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $adminUser = new User();
        $adminUser->role = 'admin';
        $adminUser->email = 'admin@square1.io';
        $adminUser->name = 'Admin';
        // Admin user has a random password at first, although it can be changed using the forgot password method
        $adminUser->password = Hash::make(Str::random(32));
        $adminUser->email_verified_at = now();
        $adminUser->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            User::where('email', 'admin@square1.io')->delete();
        });
    }
}
