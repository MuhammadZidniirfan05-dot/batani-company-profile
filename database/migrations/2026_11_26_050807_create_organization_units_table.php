
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('organization_units')
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('position')->nullable();
            $table->string('leader_name')->nullable();

            $table->text('description')->nullable();
            $table->string('photo')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_units');
    }
};
