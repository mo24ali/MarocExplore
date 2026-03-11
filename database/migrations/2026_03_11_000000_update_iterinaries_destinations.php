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
        Schema::table('iterinaries', function (Blueprint $table) {
            if (!Schema::hasColumn('iterinaries', 'category')) {
                $table->string('category')->nullable()->after('title');
            }
            if (!Schema::hasColumn('iterinaries', 'duration')) {
                $table->string('duration')->nullable()->after('category');
            }
        });

        Schema::table('destinations', function (Blueprint $table) {
            if (!Schema::hasColumn('destinations', 'iterinary_id')) {
                $table->foreignId('iterinary_id')->nullable()->constrained('iterinaries')->nullOnDelete()->after('image');
            }
            if (!Schema::hasColumn('destinations', 'places')) {
                $table->json('places')->nullable();
            }
            if (!Schema::hasColumn('destinations', 'activities')) {
                $table->json('activities')->nullable();
            }
            if (!Schema::hasColumn('destinations', 'dishes')) {
                $table->json('dishes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            if (Schema::hasColumn('destinations', 'iterinary_id')) {
                $table->dropConstrainedForeignId('iterinary_id');
            }
            if (Schema::hasColumn('destinations', 'places')) {
                $table->dropColumn('places');
            }
            if (Schema::hasColumn('destinations', 'activities')) {
                $table->dropColumn('activities');
            }
            if (Schema::hasColumn('destinations', 'dishes')) {
                $table->dropColumn('dishes');
            }
        });

        Schema::table('iterinaries', function (Blueprint $table) {
            if (Schema::hasColumn('iterinaries', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('iterinaries', 'duration')) {
                $table->dropColumn('duration');
            }
        });
    }
};