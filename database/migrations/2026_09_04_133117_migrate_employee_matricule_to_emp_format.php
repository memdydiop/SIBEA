<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Migrate legacy SIB-#### matricules to EMP-xxxx-xxxx.
     */
    public function up(): void
    {
        $employees = DB::table('employees')->where('registration_number', 'like', 'SIB-%')->get();

        foreach ($employees as $employee) {
            if (preg_match('/^SIB-(\d{4})$/', $employee->registration_number, $matches)) {
                $new = sprintf('EMP-0000-%s', $matches[1]);

                // Ensure no collision with existing EMP- entry
                if (! DB::table('employees')->where('registration_number', $new)->exists()) {
                    DB::table('employees')->where('id', $employee->id)->update(['registration_number' => $new]);
                }
            }
        }

        // Generic fallback for any remaining SIB-% not matching 4-digit pattern
        $leftovers = DB::table('employees')->where('registration_number', 'like', 'SIB-%')->get();
        foreach ($leftovers as $employee) {
            $suffix = substr($employee->registration_number, 4);
            $clean = preg_replace('/[^0-9]/', '', $suffix);
            $padded = str_pad($clean ?: (string) $employee->id, 8, '0', STR_PAD_LEFT);
            $new = sprintf('EMP-%s-%s', substr($padded, 0, 4), substr($padded, 4, 4));

            if (! DB::table('employees')->where('registration_number', $new)->exists()) {
                DB::table('employees')->where('id', $employee->id)->update(['registration_number' => $new]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $employees = DB::table('employees')->where('registration_number', 'like', 'EMP-%')->get();

        foreach ($employees as $employee) {
            if (preg_match('/^EMP-0000-(\d{4})$/', $employee->registration_number, $matches)) {
                $old = sprintf('SIB-%s', $matches[1]);
                if (! DB::table('employees')->where('registration_number', $old)->exists()) {
                    DB::table('employees')->where('id', $employee->id)->update(['registration_number' => $old]);
                }
            }
        }
    }
};
