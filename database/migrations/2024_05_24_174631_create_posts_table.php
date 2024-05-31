<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('version');
            $table->tinyText('state')->index();
            $table->text('title');
            $table->mediumText('content');
            $table->dateTime('created_at')->index();
            $table->dateTime('published_at')->nullable()->index();
            $table->dateTime('deleted_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
