<?php

use App\Models\Category;
use App\Models\Company;
use App\Models\Departament;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignIdFor(Company::class);
            $table->foreignIdFor(Departament::class);
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Subcategory::class)->nullable();
            $table->foreignIdFor(User::class, 'requester_id');
            $table->foreignIdFor(User::class, 'assignee_id')->nullable();
            $table->foreignIdFor(User::class, 'transfer_assignee_id')->nullable();
            $table->string('subject');
            $table->integer('status');
            $table->integer('action');
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
        Schema::dropIfExists('tickets');
    }
}
