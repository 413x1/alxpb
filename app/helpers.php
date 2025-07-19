<?php

use App\Models\Voucher;

if (! function_exists('isActiveRoute')) {
    function isActiveRoute($routeName, $output = 'active')
    {
        return request()->routeIs($routeName) ? $output : '';
    }
}

if (! function_exists('generateVoucherCode')) {
    /**
     * Generate unique voucher codes and save to database
     *
     * @param  int  $count  Number of voucher codes to generate
     * @param  array  $data  Additional data for each voucher (optional)
     * @return array Array of generated voucher codes
     */
    function generateVoucherCode(int $count, array $data = []): array
    {
        if ($count <= 0) {
            return [];
        }

        $generatedCodes = [];
        $maxAttempts = $count * 10;
        $attempts = 0;

        // Generate unique codes
        while (count($generatedCodes) < $count && $attempts < $maxAttempts) {
            $attempts++;

            // Generate random 4-character alphanumeric code (A-Z, 0-9)
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = '';
            for ($i = 0; $i < 4; $i++) {
                $code .= $characters[random_int(0, 35)]; // 26 letters + 10 numbers = 36 total
            }

            // Check if code is unique (not exists with is_used=true AND used_at=null)
            $exists = Voucher::where('code', $code)
                ->where('is_used', true)
                ->whereNull('used_at')
                ->exists();

            if (! $exists) {
                $generatedCodes[] = $code;
            }
        }

        // Save to a database
        foreach ($generatedCodes as $code) {
            $voucherData = array_merge([
                'code' => $code,
                'is_used' => false,
                'is_willcard' => false,
                'created_by' => auth()->id(),
            ], $data);

            Voucher::create($voucherData);
        }

        return $generatedCodes;
    }
}
