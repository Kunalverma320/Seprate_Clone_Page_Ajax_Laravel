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
        Schema::create('Product', function (Blueprint $table) {
            $table->id();
            $table->string('productname');
            $table->unsignedBigInteger('categoryid');
            $table->decimal('productprice', 8, 2);
            $table->string('productsku')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->foreign('categoryid')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Product');
    }
};
