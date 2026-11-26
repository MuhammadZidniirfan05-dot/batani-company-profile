
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('publication_categories')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();

            $table->text('abstract')->nullable();
            $table->longText('content')->nullable();

            $table->string('authors')->nullable();
            $table->string('publisher')->nullable();

            $table->date('publication_date')->nullable();

            $table->string('cover_image')->nullable();
            $table->string('document_path')->nullable();

            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();

            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('downloads_count')->default(0);

            $table->timestamps();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
