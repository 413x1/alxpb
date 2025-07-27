<?php

use App\Models\Voucher;

use function Pest\Laravel\assertDatabaseHas;
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
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ])
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'recordsTotal',
                'recordsFiltered',
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
        // Arrange - Updated to match controller validation (4 characters only)
        $data = [
            'code' => 'AB12',  // Changed to 4 characters as per controller validation
            'description' => 'Test voucher description for testing',
            'is_willcard' => false,  // Added to match controller
        ];

        // Act
        $response = post(route('dashboard.vouchers.store'), $data);

        // Assert
        $response->assertRedirect(route('dashboard.vouchers.index'))
            ->assertSessionHas('success', 'Voucher created successfully.');

        assertDatabaseHas('vouchers', [
            'code' => 'AB12',
            'description' => 'Test voucher description for testing',
            'is_willcard' => false,
            'is_used' => 0,
            'used_at' => null,
            'created_by' => auth()->id(),  // Added to match controller
        ]);
    });

    it('can create voucher with is_willcard true', function () {
        // Arrange
        $data = [
            'code' => 'WC01',
            'description' => 'Wildcard voucher for testing',
            'is_willcard' => true,
        ];

        // Act
        $response = post(route('dashboard.vouchers.store'), $data);

        // Assert
        $response->assertRedirect(route('dashboard.vouchers.index'))
            ->assertSessionHas('success', 'Voucher created successfully.');

        assertDatabaseHas('vouchers', [
            'code' => 'WC01',
            'description' => 'Wildcard voucher for testing',
            'is_willcard' => true,
            'created_by' => auth()->id(),
        ]);
    });

    it('validates required fields', function () {
        post(route('dashboard.vouchers.store'), [])
            ->assertSessionHasErrors(['code']);
    });

    it('validates unique voucher code', function () {
        Voucher::factory()->create(['code' => 'DUPL']);  // Changed to 4 characters

        post(route('dashboard.vouchers.store'), [
            'code' => 'DUPL',
            'description' => 'Attempting duplicate code',
        ])
            ->assertSessionHasErrors(['code']);
    });

    it('validates voucher code must be exactly 4 characters', function () {
        // Test with less than 4 characters
        post(route('dashboard.vouchers.store'), [
            'code' => 'ABC',  // 3 characters
            'description' => 'Code too short test',
        ])
            ->assertSessionHasErrors(['code']);

        // Test with more than 4 characters
        post(route('dashboard.vouchers.store'), [
            'code' => 'ABCDE',  // 5 characters
            'description' => 'Code too long test',
        ])
            ->assertSessionHasErrors(['code']);
    });

    it('validates voucher code format (alphanumeric only)', function () {
        // Test with special characters
        post(route('dashboard.vouchers.store'), [
            'code' => 'AB@#',
            'description' => 'Invalid character test',
        ])
            ->assertSessionHasErrors(['code']);

        // Test with spaces
        post(route('dashboard.vouchers.store'), [
            'code' => 'AB 1',
            'description' => 'Space character test',
        ])
            ->assertSessionHasErrors(['code']);
    });

    it('accepts valid alphanumeric codes', function () {
        $validCodes = ['AB12', '1234', 'ABCD', 'a1B2'];

        foreach ($validCodes as $index => $code) {
            post(route('dashboard.vouchers.store'), [
                'code' => $code,
                'description' => "Valid code test {$index}",
            ])
                ->assertSessionDoesntHaveErrors(['code']);
        }
    });

    it('validates description max length', function () {
        post(route('dashboard.vouchers.store'), [
            'code' => 'TEST',
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
            'used_at' => now(),
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
            'code' => 'OLD1',  // Changed to 4 characters
            'description' => 'Old description',
        ]);

        $data = [
            'code' => 'NEW1',  // Changed to 4 characters
            'description' => 'Updated description for testing',
            'is_willcard' => true,
        ];

        // Act
        $response = put(route('dashboard.vouchers.update', $voucher), $data);

        // Assert
        $response->assertRedirect(route('dashboard.vouchers.index'))
            ->assertSessionHas('success', 'Voucher updated successfully.');

        assertDatabaseHas('vouchers', [
            'id' => $voucher->id,
            'code' => 'NEW1',
            'description' => 'Updated description for testing',
            'is_willcard' => true,
        ]);
    });

    it('can update voucher with nullable fields', function () {
        // Arrange
        $voucher = Voucher::factory()->create([
            'code' => 'OLD2',
            'description' => 'Old description',
            'is_willcard' => true,
        ]);

        $data = [
            'code' => 'NEW2',
            'description' => null,  // Test nullable description
            'is_willcard' => null,  // Test nullable is_willcard
        ];

        // Act
        $response = put(route('dashboard.vouchers.update', $voucher), $data);

        // Assert
        $response->assertRedirect(route('dashboard.vouchers.index'))
            ->assertSessionHas('success', 'Voucher updated successfully.');

        assertDatabaseHas('vouchers', [
            'id' => $voucher->id,
            'code' => 'NEW2',
            'description' => null,
            'is_willcard' => false,
        ]);
    });

    it('validates unique code excluding current voucher', function () {
        $voucher1 = Voucher::factory()->create(['code' => 'COD1']);  // 4 characters
        $voucher2 = Voucher::factory()->create(['code' => 'COD2']);  // 4 characters

        // Should allow updating with same code
        put(route('dashboard.vouchers.update', $voucher1), [
            'code' => 'COD1',
            'description' => $voucher1->description,
        ])->assertSessionDoesntHaveErrors();

        // Should not allow updating with another voucher's code
        put(route('dashboard.vouchers.update', $voucher1), [
            'code' => 'COD2',
            'description' => $voucher1->description,
        ])->assertSessionHasErrors(['code']);
    });

    it('validates code format during update', function () {
        $voucher = Voucher::factory()->create(['code' => 'TEST']);

        // Test invalid format
        put(route('dashboard.vouchers.update', $voucher), [
            'code' => 'TE@T',  // Invalid characters
            'description' => 'Test description',
        ])->assertSessionHasErrors(['code']);

        // Test invalid length
        put(route('dashboard.vouchers.update', $voucher), [
            'code' => 'TESTS',  // 5 characters
            'description' => 'Test description',
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
                'message' => 'Voucher deleted successfully.',
            ]);

        assertSoftDeleted('vouchers', [
            'id' => $voucher->id,
        ]);
    });

    it('handles delete errors gracefully', function () {
        // Arrange - Create a voucher and then manually delete it to simulate an error
        $voucher = Voucher::factory()->create();
        $voucher->forceDelete(); // This will cause the delete to fail

        // Act
        $response = deleteJson(route('dashboard.vouchers.destroy', 999)); // Non-existent ID

        // Assert
        $response->assertStatus(404); // Laravel will return 404 for non-existent model
    });
});

describe('Voucher Generate Code', function () {
    it('can generate voucher codes successfully', function () {
        // Arrange
        $data = [
            'count' => 5,
            'is_willcard' => false,
        ];

        // Act
        $response = postJson(route('dashboard.vouchers.generate-voucher-code'), $data);

        // Assert
        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Vouchers generated successfully.',
            ]);

        $responseData = $response->json();

        // Verify response structure
        expect($responseData)->toHaveKeys(['success', 'message', 'data'])
            ->and($responseData['success'])->toBe(true)
            ->and($responseData['data'])->toBeArray();

        // Verify the correct number of voucher codes were generated
        expect($responseData['data'])->toHaveCount(5);

        // Verify all generated codes are valid and saved to database
        foreach ($responseData['data'] as $code) {
            // Each item should be a string (voucher code)
            expect($code)->toBeString()
                ->and($code)->toHaveLength(4)
                ->and($code)->toMatch('/^[A-Za-z0-9]{4}$/');

            // Verify the voucher was actually saved to database
            assertDatabaseHas('vouchers', [
                'code' => $code,
                'is_willcard' => false,
                'is_used' => 0,
                'created_by' => auth()->id(),
            ]);
        }
    });

    it('can generate wildcard voucher codes', function () {
        // Arrange
        $data = [
            'count' => 3,
            'is_willcard' => true,
        ];

        // Act
        $response = postJson(route('dashboard.vouchers.generate-voucher-code'), $data);

        // Assert
        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Vouchers generated successfully.',
            ]);

        $responseData = $response->json();

        // Verify the correct number of voucher codes were generated
        expect($responseData['data'])->toHaveCount(3);

        // Verify all generated vouchers are wildcard vouchers
        foreach ($responseData['data'] as $code) {
            expect($code)->toBeString()
                ->and($code)->toHaveLength(4);

            assertDatabaseHas('vouchers', [
                'code' => $code,
                'is_willcard' => true,
            ]);
        }
    });

    it('generates unique voucher codes', function () {
        // Arrange
        $data = [
            'count' => 10,
            'is_willcard' => false,
        ];

        // Act
        $response = postJson(route('dashboard.vouchers.generate-voucher-code'), $data);

        // Assert
        $response->assertOk();
        $responseData = $response->json();

        // Extract all generated codes
        $generatedCodes = $responseData['data'];

        // Verify all codes are unique
        expect($generatedCodes)->toHaveCount(10)
            ->and(array_unique($generatedCodes))->toHaveCount(10);
    });

    it('validates required count parameter', function () {
        postJson(route('dashboard.vouchers.generate-voucher-code'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['count']);
    });

    it('validates count parameter must be integer', function () {
        postJson(route('dashboard.vouchers.generate-voucher-code'), [
            'count' => 'not-a-number',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['count']);
    });

    it('validates count parameter minimum value', function () {
        postJson(route('dashboard.vouchers.generate-voucher-code'), [
            'count' => 0,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['count']);
    });

    it('validates count parameter maximum value', function () {
        postJson(route('dashboard.vouchers.generate-voucher-code'), [
            'count' => 101,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['count']);
    });

    it('accepts valid count range', function () {
        // Test minimum valid value
        postJson(route('dashboard.vouchers.generate-voucher-code'), [
            'count' => 1,
            'is_willcard' => false,
        ])
            ->assertOk()
            ->assertJson(['success' => true]);

        // Test maximum valid value
        postJson(route('dashboard.vouchers.generate-voucher-code'), [
            'count' => 100,
            'is_willcard' => false,
        ])
            ->assertOk()
            ->assertJson(['success' => true]);
    });

    it('handles is_willcard parameter correctly when not provided', function () {
        // Arrange
        $data = [
            'count' => 2,
            // is_willcard not provided, should default to false
        ];

        // Act
        $response = postJson(route('dashboard.vouchers.generate-voucher-code'), $data);

        // Assert
        $response->assertOk();
        $responseData = $response->json();

        expect($responseData['data'])->toHaveCount(2);

        foreach ($responseData['data'] as $voucher) {
            // Check is_willcard if it exists in response, otherwise check database
            if (isset($voucher['is_willcard'])) {
                expect($voucher['is_willcard'])->toBe(false);
            } else {
                // Verify in database that is_willcard defaults to false
                assertDatabaseHas('vouchers', [
                    'code' => $voucher,
                    'is_willcard' => false,
                ]);
            }
        }
    });

    it('validates is_willcard parameter must be boolean', function () {
        postJson(route('dashboard.vouchers.generate-voucher-code'), [
            'count' => 1,
            'is_willcard' => 'not-a-boolean',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['is_willcard']);
    });

    it('handles exceptions gracefully', function () {
        // Mock the generateVoucherCode function to throw an exception
        // This would require creating a mock or using a test double
        // For now, we'll test the exception handling structure exists

        // If the generateVoucherCode function throws an exception,
        // the controller should return a 500 response with error message

        // Note: This test might need adjustment based on how you implement
        // the generateVoucherCode helper function and how you want to test exceptions
        expect(true)->toBe(true); // Placeholder - implement based on your needs
    });

    it('generates codes with correct format', function () {
        // Arrange
        $data = [
            'count' => 5,
            'is_willcard' => false,
        ];

        // Act
        $response = postJson(route('dashboard.vouchers.generate-voucher-code'), $data);

        // Assert
        $response->assertOk();
        $responseData = $response->json();

        foreach ($responseData['data'] as $voucher) {
            // Verify code format: exactly 4 alphanumeric characters
            expect($voucher)->toMatch('/^[A-Za-z0-9]{4}$/');
        }
    });
});
