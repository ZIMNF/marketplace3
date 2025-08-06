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
        Schema::table('products', function (Blueprint $table) {
            // Remove all extra columns except the required ones
            $table->dropColumn([
                'category',
                'brand',
                'skin_type',
                'volume',
                'ingredients',
                'how_to_use',
                'benefits',
                'warnings',
                'is_active'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add back the columns if needed
            $table->string('category')->nullable()->after('description');
            $table->string('brand')->nullable()->after('category');
            $table->string('skin_type')->nullable()->after('brand');
            $table->string('volume')->nullable()->after('skin_type');
            $table->json('ingredients')->nullable()->after('volume');
            $table->text('how_to_use')->nullable()->after('ingredients');
            $table->json('benefits')->nullable()->after('how_to_use');
            $table->json('warnings')->nullable()->after('benefits');
            $table->boolean('is_active')->default(true)->after('created_by');
        });
    }
};
