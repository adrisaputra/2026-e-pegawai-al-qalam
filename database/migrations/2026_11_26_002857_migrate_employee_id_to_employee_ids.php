<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('employee_reports')
        ->whereNotNull('employee_id')
        ->orderBy('id')
        ->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                DB::table('employee_reports')
                    ->where('id', $row->id)
                    ->update([
                        'employee_ids' => json_encode([
                            (string) $row->employee_id
                        ]),
                    ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_ids', function (Blueprint $table) {
            //
        });
    }
};
