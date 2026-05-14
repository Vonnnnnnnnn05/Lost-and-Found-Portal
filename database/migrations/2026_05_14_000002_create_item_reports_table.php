<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_reports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('title');
            $table->string('category');
            $table->text('description');
            $table->string('location');
            $table->date('item_date');
            $table->string('image_path')->nullable();
            $table->string('status', 20)->default('pending')->index();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['type', 'category']);
            $table->index('item_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_reports');
    }
};
