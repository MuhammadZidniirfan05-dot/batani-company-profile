
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('it_projects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('project_categories')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();

            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();

            $table->string('client')->nullable();

            $table->string('thumbnail')->nullable();
            $table->json('gallery')->nullable();
            $table->json('technologies')->nullable();

            $table->string('project_url')->nullable();
            $table->string('demo_url')->nullable();

            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();

            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();

            $table->unsignedBigInteger('views_count')->default(0);

            $table->timestamps();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('it_projects');
    }
};
