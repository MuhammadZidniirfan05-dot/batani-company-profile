
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_categories', function (Blueprint $table) {
            $table->id();

            // Informasi kategori
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Pengaturan tampilan
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            // Timestamp
            $table->timestamps();

            // Index untuk pengurutan dan filter
            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_categories');
    }
};
