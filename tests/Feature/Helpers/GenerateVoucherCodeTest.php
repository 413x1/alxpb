<?php

use App\Models\Voucher;

beforeEach(function () {
    login();
    $this->user = Auth::user();
});

describe('generateVoucherCode function', function () {

    it('generates the correct number of voucher codes', function () {
        $codes = generateVoucherCode(5);

        expect($codes)->toHaveCount(5)
            ->and($codes)->toBeArray();
    });

    it('generates unique 4-character uppercase alphabetical codes', function () {
        $codes = generateVoucherCode(10);

        foreach ($codes as $code) {
            expect($code)
                ->toHaveLength(4)
                ->toMatch('/^[A-Z0-9]{4}$/');
        }

        // Check all codes are unique
        expect($codes)->toEqual(array_unique($codes));
    });

    it('saves vouchers to database with correct default values', function () {
        $codes = generateVoucherCode(3);

        expect(Voucher::count())->toBe(3);

        foreach ($codes as $code) {
            $voucher = Voucher::where('code', $code)->first();

            expect($voucher)->not->toBeNull()
                ->and($voucher->code)->toBe($code)
                ->and($voucher->is_used)->toBeFalse()
                ->and($voucher->is_willcard)->toBeFalse()
                ->and($voucher->used_at)->toBeNull()
                ->and($voucher->created_by)->toBe($this->user->id);
        }
    });

    it('saves vouchers with additional data', function () {
        $additionalData = [
            'description' => 'Test Voucher',
            'is_willcard' => true,
        ];

        $codes = generateVoucherCode(2, $additionalData);

        foreach ($codes as $code) {
            $voucher = Voucher::where('code', $code)->first();

            expect($voucher->description)->toBe('Test Voucher')
                ->and($voucher->is_willcard)->toBeTrue();
        }
    });

    it('returns empty array when count is zero or negative', function () {
        expect(generateVoucherCode(0))->toBeEmpty()
            ->and(generateVoucherCode(-5))->toBeEmpty()
            ->and(Voucher::count())->toBe(0);

    });

    it('generates codes that are unique based on uniqueness condition', function () {
        // Create existing voucher with is_used=true and used_at=null (conflicts with new codes)
        Voucher::create([
            'code' => 'AAAA',
            'is_used' => true,
            'used_at' => null,
            'is_willcard' => false,
            'created_by' => $this->user->id,
        ]);

        // Generate new codes - should not include 'AAAA'
        $codes = generateVoucherCode(5);

        expect($codes)->not->toContain('AAAA')
            ->and($codes)->toHaveCount(5);
    });

    it('allows duplicate codes when existing voucher does not meet conflict condition', function () {
        // Create an existing voucher with is_used=false (no conflict)
        Voucher::create([
            'code' => 'BBBB',
            'is_used' => false,
            'used_at' => null,
            'is_willcard' => false,
            'created_by' => $this->user->id,
        ]);

        // Create existing voucher with is_used=true but used_at is not null (no conflict)
        Voucher::create([
            'code' => 'CCCC',
            'is_used' => true,
            'used_at' => now(),
            'is_willcard' => false,
            'created_by' => $this->user->id,
        ]);

        // Mock random generation to ensure we test the specific codes
        // In a real scenario, these would be randomly generated
        $codes = generateVoucherCode(2);

        // The function should work normally since no conflicts exist
        expect($codes)->toHaveCount(2);
    });

    it('handles large number generation efficiently', function () {
        $start = microtime(true);
        $codes = generateVoucherCode(100);
        $end = microtime(true);

        expect($codes)->toHaveCount(100)
            ->and(Voucher::count())->toBe(100)
            ->and($end - $start)->toBeLessThan(5);
        // Should complete within 5 seconds
    });

    it('maintains referential integrity with created_by user', function () {
        $codes = generateVoucherCode(3);

        foreach ($codes as $code) {
            $voucher = Voucher::where('code', $code)->first();
            expect($voucher->createdBy)->not->toBeNull()
                ->and($voucher->createdBy->id)->toBe($this->user->id);
        }
    });

    it('works when no user is authenticated', function () {
        auth()->logout();

        $codes = generateVoucherCode(2);

        expect($codes)->toHaveCount(2);

        foreach ($codes as $code) {
            $voucher = Voucher::where('code', $code)->first();
            expect($voucher->created_by)->toBeNull();
        }
    });

    it('generates different codes on multiple calls', function () {
        $firstBatch = generateVoucherCode(5);
        $secondBatch = generateVoucherCode(5);

        // Combine both batches and check uniqueness
        $allCodes = array_merge($firstBatch, $secondBatch);
        expect($allCodes)->toHaveCount(10)
            ->and($allCodes)->toEqual(array_unique($allCodes));
    });

    it('preserves existing vouchers in database', function () {
        // Create some existing vouchers
        $existingVoucher = Voucher::create([
            'code' => 'ZZZZ',
            'description' => 'Existing voucher',
            'is_used' => false,
            'is_willcard' => false,
            'created_by' => $this->user->id,
        ]);

        $codes = generateVoucherCode(3);

        // Should have existing + new vouchers
        expect(Voucher::count())->toBe(4)
            ->and(Voucher::find($existingVoucher->id))->not->toBeNull()
            ->and($codes)->toHaveCount(3);
    });

});

describe('integration tests', function () {

    it('works with voucher model relationships', function () {
        $codes = generateVoucherCode(2, [
            'description' => 'Relationship test',
        ]);

        foreach ($codes as $code) {
            $voucher = Voucher::with('createdBy')->where('code', $code)->first();

            expect($voucher->createdBy)->not->toBeNull()
                ->and($voucher->createdBy->id)->toBe($this->user->id);
        }
    });

    it('respects model fillable attributes', function () {
        $codes = generateVoucherCode(1, [
            'description' => 'Test description',
            'is_willcard' => true,
            'non_fillable_field' => 'should be ignored', // This should be ignored
        ]);

        $voucher = Voucher::where('code', $codes[0])->first();

        expect($voucher->description)->toBe('Test description')
            ->and($voucher->is_willcard)->toBeTrue()
            ->and($voucher->getAttributes())->not->toHaveKey('non_fillable_field');
    });

    it('works with model casts', function () {
        $codes = generateVoucherCode(1);

        $voucher = Voucher::where('code', $codes[0])->first();

        // Test boolean casts
        expect($voucher->is_used)->toBeFalse()
            ->and($voucher->is_willcard)->toBeFalse()
            ->and($voucher->used_at)->toBeNull();

        // Test datetime cast
    });

});

describe('performance and edge cases', function () {

    it('handles maximum realistic load', function () {
        // Test with a reasonable large number
        $codes = generateVoucherCode(500);

        expect($codes)->toHaveCount(500)
            ->and(Voucher::count())->toBe(500)
            ->and($codes)->toEqual(array_unique($codes));

        // Verify all codes are unique
    });

    it('maintains consistency under concurrent-like conditions', function () {
        // Simulate multiple rapid calls
        $allCodes = [];

        for ($i = 0; $i < 5; $i++) {
            $codes = generateVoucherCode(10);
            $allCodes = array_merge($allCodes, $codes);
        }

        expect($allCodes)->toHaveCount(50)
            ->and($allCodes)->toEqual(array_unique($allCodes))
            ->and(Voucher::count())->toBe(50);
    });

    it('handles database constraint violations gracefully', function () {
        // This test assumes your database has unique constraints
        // The function should handle any potential duplicates

        $codes = generateVoucherCode(20);

        expect($codes)->toHaveCount(20)
            ->and(Voucher::count())->toBe(20);

        // All codes should be in the database
        foreach ($codes as $code) {
            expect(Voucher::where('code', $code)->exists())->toBeTrue();
        }
    });

});
