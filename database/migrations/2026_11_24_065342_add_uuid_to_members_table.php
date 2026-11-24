<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('members', 'uuid')) {
            Schema::table('members', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->unique();
            });
        }

        DB::table('members')
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunkById(100, function ($members): void {
                foreach ($members as $member) {
                    DB::table('members')
                        ->where('id', $member->id)
                        ->update(['uuid' => (string) Str::uuid()]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('members', 'uuid')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropUnique(['uuid']);
                $table->dropColumn('uuid');
            });
        }
    }
};
