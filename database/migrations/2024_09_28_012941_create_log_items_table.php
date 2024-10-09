<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Log::class);
            $table->foreignIdFor(App\Models\User::class);
            $table->string('label')->nullable();
            $table->string('type');
            $table->integer('status');
            $table->longText('action');
            $table->longText('body')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_items');
    }
}
