<?php

use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    login();
});

describe('User Index', function () {
    it('can display users list page', function () {
        // Arrange
        User::factory()->count(5)->create();

        // Act & Assert
        get(route('dashboard.users.index'))
            ->assertOk()
            ->assertViewIs('pages.admin.user.index')
            ->assertViewHas('users');
    });

    it('paginates users correctly', function () {
        // Create 15 users (more than per page)
        User::factory()->count(15)->create();

        get(route('dashboard.users.index'))
            ->assertOk()
            ->assertViewHas('users', function ($users) {
                return $users->count() === 10; // Per page is 10
            });
    });
});

describe('User Create', function () {
    it('can display create user form', function () {
        get(route('dashboard.users.create'))
            ->assertOk()
            ->assertViewIs('pages.admin.user.create');
    });

    it('can create a new user', function () {
        // Arrange
        $data = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'admin',
            'is_active' => '1',
        ];

        // Act
        $response = post(route('dashboard.users.store'), $data);

        // Assert
        $response->assertRedirect(route('dashboard.users.index'))
            ->assertSessionHas('success', 'User created successfully.');

        assertDatabaseHas('users', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'role' => 'admin',
            'is_active' => true,
        ]);
    });

    it('creates inactive user when is_active is not checked', function () {
        $data = [
            'name' => 'Inactive User',
            'username' => 'inactiveuser',
            'email' => 'inactive@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ];

        post(route('dashboard.users.store'), $data);

        assertDatabaseHas('users', [
            'name' => 'Inactive User',
            'is_active' => false,
        ]);
    });

    it('validates required fields', function () {
        post(route('dashboard.users.store'), [])
            ->assertSessionHasErrors(['name', 'username', 'email', 'password', 'role']);
    });

    it('validates unique username', function () {
        User::factory()->create(['username' => 'johndoe']);

        post(route('dashboard.users.store'), [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ])
            ->assertSessionHasErrors(['username']);
    });

    it('validates unique email', function () {
        User::factory()->create(['email' => 'john@example.com']);

        post(route('dashboard.users.store'), [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ])
            ->assertSessionHasErrors(['email']);
    });

    it('validates role is in allowed values', function () {
        post(route('dashboard.users.store'), [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'invalid_role',
        ])
            ->assertSessionHasErrors(['role']);
    });

    it('hashes password when creating user', function () {
        $data = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'admin',
            'is_active' => true,
        ];

        post(route('dashboard.users.store'), $data);

        $user = User::where('email', 'john@example.com')->first();
        expect($user->password)->not->toBe('password123')
            ->and(Hash::check('password123', $user->password))->toBeTrue();
    });
});

describe('User Edit', function () {
    it('can display edit user form', function () {
        // Arrange
        $user = User::factory()->create();

        // Act & Assert
        get(route('dashboard.users.edit', $user))
            ->assertOk()
            ->assertViewIs('pages.admin.user.edit')
            ->assertViewHas('user', $user);
    });

    it('can update user', function () {
        // Arrange
        $user = User::factory()->create([
            'name' => 'Old Name',
            'username' => 'oldusername',
            'email' => 'old@example.com',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $data = [
            'name' => 'Updated Name',
            'username' => 'newusername',
            'email' => 'new@example.com',
            'role' => 'admin',
            'is_active' => true,
        ];

        // Act
        $response = put(route('dashboard.users.update', $user), $data);

        // Assert
        $response->assertRedirect(route('dashboard.users.index'))
            ->assertSessionHas('success', 'User updated successfully.');

        assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'username' => 'newusername',
            'email' => 'new@example.com',
            'role' => 'admin',
            'is_active' => true,
        ]);
    });

    it('can update user password', function () {
        $user = User::factory()->create();

        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'newpassword123',
            'role' => $user->role,
            'is_active' => true,
        ];

        put(route('dashboard.users.update', $user), $data);

        $user->refresh();
        expect(Hash::check('newpassword123', $user->password))->toBeTrue();
    });

    it('does not update password when field is empty', function () {
        $user = User::factory()->create();
        $originalPassword = $user->password;

        $data = [
            'name' => 'Updated Name',
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => true,
        ];

        put(route('dashboard.users.update', $user), $data);

        $user->refresh();
        expect($user->password)->toBe($originalPassword);
    });

    it('validates unique username excluding current user', function () {
        $user1 = User::factory()->create(['username' => 'user1']);
        $user2 = User::factory()->create(['username' => 'user2']);

        // Should allow updating with same username
        put(route('dashboard.users.update', $user1), [
            'name' => $user1->name,
            'username' => 'user1',
            'email' => $user1->email,
            'role' => $user1->role,
        ])->assertSessionDoesntHaveErrors();

        // Should not allow updating with another user's username
        put(route('dashboard.users.update', $user1), [
            'name' => $user1->name,
            'username' => 'user2',
            'email' => $user1->email,
            'role' => $user1->role,
        ])->assertSessionHasErrors(['username']);
    });

    it('validates unique email excluding current user', function () {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        // Should allow updating with same email
        put(route('dashboard.users.update', $user1), [
            'name' => $user1->name,
            'username' => $user1->username,
            'email' => 'user1@example.com',
            'role' => $user1->role,
        ])->assertSessionDoesntHaveErrors();

        // Should not allow updating with another user's email
        put(route('dashboard.users.update', $user1), [
            'name' => $user1->name,
            'username' => $user1->username,
            'email' => 'user2@example.com',
            'role' => $user1->role,
        ])->assertSessionHasErrors(['email']);
    });
});

describe('User Delete', function () {
    it('can delete a user', function () {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = delete(route('dashboard.users.destroy', $user));

        // Assert
        $response->assertRedirect(route('dashboard.users.index'))
            ->assertSessionHas('success', 'User deleted successfully.');

        assertDatabaseMissing('users', ['id' => $user->id]);
    });
});
