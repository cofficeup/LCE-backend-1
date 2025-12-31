<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HealthController
{
    public function __invoke()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'down',
                'database' => false,
            ], 503);
        }

        return response()->json([
            'status' => 'ok',
            'database' => true,
        ]);
    }
}
