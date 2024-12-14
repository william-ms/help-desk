<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'notified_id');
            $table->foreignIdFor(User::class, 'notifier_id')->nullable();
            $table->integer('status')->default(1);  // 1 - Enviada; 2 - Visualizada
            $table->string('model_type');
            $table->integer('model_id'); 
            $table->longText('message');
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
