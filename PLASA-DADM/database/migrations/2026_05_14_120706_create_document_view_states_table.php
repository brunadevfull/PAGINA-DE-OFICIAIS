<?php

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
       // Schema::create('document_view_states', function (Blueprint $table) {
         //   $table->id();
           // $table->timestamps();
       // });
	Schema::create('document_view_states', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('document_id')->unique();
    $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
    $table->float('zoom')->default(1.0);
    $table->integer('scroll_top')->default(0);
    $table->integer('scroll_left')->default(0);
    $table->timestamp('updated_at')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_view_states');
    }
};
