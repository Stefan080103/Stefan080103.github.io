<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('email_notifications')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('show_activity_status')->default(true);
            $table->boolean('allow_message_requests')->default(true);
            $table->enum('account_privacy', ['public', 'private'])->default('public');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_settings');
    }
};
