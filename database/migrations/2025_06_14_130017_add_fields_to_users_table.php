<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->text('bio')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('phone')->nullable()->after('avatar');
            $table->date('birth_date')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birth_date');
            $table->boolean('is_private')->default(false)->after('gender');
            $table->integer('followers_count')->default(0)->after('is_private');
            $table->integer('following_count')->default(0)->after('followers_count');
            $table->integer('posts_count')->default(0)->after('following_count');
            $table->timestamp('last_active_at')->nullable()->after('posts_count');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'bio', 
                'avatar',
                'phone',
                'birth_date',
                'gender',
                'is_private',
                'followers_count',
                'following_count', 
                'posts_count',
                'last_active_at'
            ]);
        });
    }
};
