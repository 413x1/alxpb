<?php

use App\Models\Voucher;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;
use function Pest\Laravel\put;

beforeEach(function () {
    login();
});

describe('Voucher Index', function () {
    it('can display vouchers list page', function () {
        get(route('dashboard.vouchers.index'))
            ->assertOk()
            ->assertViewIs('pages.admin.voucher.index');
    });

    it('can display vouchers datatable data', function () {
        // Arrange
        Voucher::factory()->count(5)->create();

        // Act & Assert
        get(route('dashboard.datatable.vouchers'), [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'recordsTotal',
                'recordsFiltered'
            ]);
    });

    it('shows correct datatable structure and data', function () {
        // Arrange
        Voucher::factory()->count(3)->create();

        // Act
        $response = get(route('dashboard.datatable.vouchers'), [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
            'draw' => 1,
            'start' => 0,
            'length' => 10,
        ]);

        // Assert
        $response->assertOk();

        $data = $response->json();
        expect($data)->toHaveKeys(['draw', 'recordsTotal', 'recordsFiltered', 'data'])
            ->and($data['data'])->toHaveCount(3);

        // Check if the action column contains expected buttons
        foreach ($data['data'] as $row) {
            expect($row['action'])->toContain('editVoucher')
                ->and($row['action'])->toContain('deleteVoucher');
        }
    });
});

describe('Voucher Create', function () {
    it('can display create voucher form', function () {
        get(route('dashboard.vouchers.create'))
            ->assertOk()
            ->assertViewIs('pages.admin.voucher.create');
    });

    it('can create a new voucher', function () {
        // Arrange
        $data = [
            'code' => 'TEST-VOUCHER-123',
            'description' => 'Test voucher description for testing',
        ];

        // Act
        $response = post(route('dashboard.vouchers.store'), $data);

        // Assert
        $response->assertRedirect(route('dashboard.vouchers.index'))
            ->assertSessionHas('success', 'Voucher created successfully.');

        assertDatabaseHas('vouchers', [
            'code' => 'TEST-VOUCHER-123',
            'description' => 'Test voucher description for testing',
            'is_willcard' => 0,
            'is_used' => 0,
            'used_at' => null,
        ]);
    });

    it('validates required fields', function () {
        post(route('dashboard.vouchers.store'), [])
            ->assertSessionHasErrors(['code', 'description']);
    });

    it('validates unique voucher code', function () {
        Voucher::factory()->create(['code' => 'DUPLICATE-CODE']);

        post(route('dashboard.vouchers.store'), [
            'code' => 'DUPLICATE-CODE',
            'description' => 'Attempting duplicate code',
        ])
            ->assertSessionHasErrors(['code']);
    });

    it('validates voucher code max length', function () {
        post(route('dashboard.vouchers.store'), [
            'code' => str_repeat('A', 51), // 51 characters
            'description' => 'Code too long test',
        ])
            ->assertSessionHasErrors(['code']);
    });

    it('validates description max length', function () {
        post(route('dashboard.vouchers.store'), [
            'code' => 'VALID-CODE',
            'description' => str_repeat('A', 1001), // 1001 characters
        ])
            ->assertSessionHasErrors(['description']);
    });
});

describe('Voucher Edit', function () {
    it('can display edit voucher form', function () {
        // Arrange
        $voucher = Voucher::factory()->create([
            'is_used' => true,
            'used_at' => now()
        ]);

        // Act & Assert
        get(route('dashboard.vouchers.edit', $voucher))
            ->assertOk()
            ->assertViewIs('pages.admin.voucher.edit')
            ->assertViewHas('voucher', $voucher);
    });

    it('can update voucher', function () {
        // Arrange
        $voucher = Voucher::factory()->create([
            'code' => 'OLD-CODE',
            'description' => 'Old description',
        ]);

        $data = [
            'code' => 'UPDATED-CODE-123',
            'description' => 'Updated description for testing',
        ];

        // Act
        $response = put(route('dashboard.vouchers.update', $voucher), $data);

        // Assert
        $response->assertRedirect(route('dashboard.vouchers.index'))
            ->assertSessionHas('success', 'Voucher updated successfully.');

        assertDatabaseHas('vouchers', [
            'id' => $voucher->id,
            'code' => 'UPDATED-CODE-123',
            'description' => 'Updated description for testing',
        ]);
    });

    it('validates unique code excluding current voucher', function () {
        $voucher1 = Voucher::factory()->create(['code' => 'CODE-001']);
        $voucher2 = Voucher::factory()->create(['code' => 'CODE-002']);

        // Should allow updating with same code
        put(route('dashboard.vouchers.update', $voucher1), [
            'code' => 'CODE-001',
            'description' => $voucher1->description,
        ])->assertSessionDoesntHaveErrors();

        // Should not allow updating with another voucher's code
        put(route('dashboard.vouchers.update', $voucher1), [
            'code' => 'CODE-002',
            'description' => $voucher1->description,
        ])->assertSessionHasErrors(['code']);
    });
});

describe('Voucher Delete', function () {
    it('can delete a voucher via ajax', function () {
        // Arrange
        $voucher = Voucher::factory()->create();

        // Act
        $response = deleteJson(route('dashboard.vouchers.destroy', $voucher));

        // Assert
        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Voucher deleted successfully.'
            ]);

        assertSoftDeleted('vouchers', [
            'id' => $voucher->id,
        ]);
    });
});
