<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

beforeEach(function () {
    login();
});

describe('Setting Index', function () {
    it('can display settings page', function () {
        // Act & Assert
        get(route('dashboard.settings.index'))
            ->assertOk()
            ->assertViewIs('pages.admin.setting.index')
            ->assertViewHas('user', auth()->user());
    });

    it('shows current user data on settings page', function () {
        $user = auth()->user();

        get(route('dashboard.settings.index'))
            ->assertOk()
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id
                    && $viewUser->name === $user->name
                    && $viewUser->email === $user->email
                    && $viewUser->username === $user->username;
            });
    });
});

describe('Setting Update', function () {
    it('can update user profile without password', function () {
        // Arrange
        $user = auth()->user();
        $originalPassword = $user->password;

        $data = [
            'name' => 'Updated Name',
            'username' => 'updated_username',
            'email' => 'updated@example.com',
        ];

        // Act
        $response = put(route('dashboard.settings.update'), $data);

        // Assert
        $response->assertRedirect(route('dashboard.settings.index'))
            ->assertSessionHas('success', 'Settings updated successfully.');

        assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'username' => 'updated_username',
            'email' => 'updated@example.com',
        ]);

        // Password should remain unchanged
        expect($user->fresh()->password)->toBe($originalPassword);
    });

    it('can update user profile with password', function () {
        // Arrange
        $user = auth()->user();
        $newPassword = 'newpassword123';

        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'password' => $newPassword,
        ];

        // Act
        put(route('dashboard.settings.update'), $data);

        // Assert
        $user->refresh();
        expect(Hash::check($newPassword, $user->password))->toBeTrue();
    });

    it('can update only some fields', function () {
        // Arrange
        $user = auth()->user();
        $originalEmail = $user->email;
        $originalUsername = $user->username;

        $data = [
            'name' => 'Only Name Updated',
            'username' => $originalUsername,
            'email' => $originalEmail,
        ];

        // Act
        put(route('dashboard.settings.update'), $data);

        // Assert
        assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Only Name Updated',
            'username' => $originalUsername,
            'email' => $originalEmail,
        ]);
    });

    it('validates required fields', function () {
        put(route('dashboard.settings.update'), [])
            ->assertSessionHasErrors(['name', 'username', 'email']);
    });

    it('validates name field', function () {
        $user = auth()->user();

        // Test empty name
        put(route('dashboard.settings.update'), [
            'name' => '',
            'username' => $user->username,
            'email' => $user->email,
        ])->assertSessionHasErrors(['name']);

        // Test name too long
        put(route('dashboard.settings.update'), [
            'name' => str_repeat('a', 256),
            'username' => $user->username,
            'email' => $user->email,
        ])->assertSessionHasErrors(['name']);
    });

    it('validates username field', function () {
        $user = auth()->user();

        // Test empty username
        put(route('dashboard.settings.update'), [
            'name' => $user->name,
            'username' => '',
            'email' => $user->email,
        ])->assertSessionHasErrors(['username']);

        // Test username too long
        put(route('dashboard.settings.update'), [
            'name' => $user->name,
            'username' => str_repeat('a', 256),
            'email' => $user->email,
        ])->assertSessionHasErrors(['username']);
    });

    it('validates email field', function () {
        $user = auth()->user();

        // Test empty email
        put(route('dashboard.settings.update'), [
            'name' => $user->name,
            'username' => $user->username,
            'email' => '',
        ])->assertSessionHasErrors(['email']);

        // Test invalid email format
        put(route('dashboard.settings.update'), [
            'name' => $user->name,
            'username' => $user->username,
            'email' => 'invalid-email',
        ])->assertSessionHasErrors(['email']);

        // Test email too long
        put(route('dashboard.settings.update'), [
            'name' => $user->name,
            'username' => $user->username,
            'email' => str_repeat('a', 250).'@example.com',
        ])->assertSessionHasErrors(['email']);
    });

    it('validates password minimum length', function () {
        $user = auth()->user();

        put(route('dashboard.settings.update'), [
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'password' => '1234567', // 7 characters, less than 8
        ])->assertSessionHasErrors(['password']);
    });

    it('validates unique username excluding current user', function () {
        // Arrange
        $currentUser = auth()->user();
        $otherUser = User::factory()->create(['username' => 'existing_username']);

        // Should allow updating with same username
        put(route('dashboard.settings.update'), [
            'name' => $currentUser->name,
            'username' => $currentUser->username,
            'email' => $currentUser->email,
        ])->assertSessionDoesntHaveErrors();

        // Should not allow updating with another user's username
        put(route('dashboard.settings.update'), [
            'name' => $currentUser->name,
            'username' => $otherUser->username,
            'email' => $currentUser->email,
        ])->assertSessionHasErrors(['username']);
    });

    it('validates unique email excluding current user', function () {
        // Arrange
        $currentUser = auth()->user();
        $otherUser = User::factory()->create(['email' => 'existing@example.com']);

        // Should allow updating with same email
        put(route('dashboard.settings.update'), [
            'name' => $currentUser->name,
            'username' => $currentUser->username,
            'email' => $currentUser->email,
        ])->assertSessionDoesntHaveErrors();

        // Should not allow updating with another user's email
        put(route('dashboard.settings.update'), [
            'name' => $currentUser->name,
            'username' => $currentUser->username,
            'email' => $otherUser->email,
        ])->assertSessionHasErrors(['email']);
    });

    it('allows empty password field', function () {
        // Arrange
        $user = auth()->user();

        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'password' => '',
        ];

        // Act & Assert
        put(route('dashboard.settings.update'), $data)
            ->assertSessionDoesntHaveErrors();
    });

    it('allows null password field', function () {
        // Arrange
        $user = auth()->user();

        $data = [
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            // password field is not included (null)
        ];

        // Act & Assert
        put(route('dashboard.settings.update'), $data)
            ->assertSessionDoesntHaveErrors();
    });

    it('shows custom validation error messages', function () {
        $currentUser = auth()->user();
        $otherUser = User::factory()->create([
            'username' => 'taken_username',
            'email' => 'taken@example.com',
        ]);

        // Test username unique message
        put(route('dashboard.settings.update'), [
            'name' => $currentUser->name,
            'username' => $otherUser->username,
            'email' => $currentUser->email,
        ])->assertSessionHasErrors([
            'username' => 'The username has already been taken.',
        ]);

        // Test email unique message
        put(route('dashboard.settings.update'), [
            'name' => $currentUser->name,
            'username' => $currentUser->username,
            'email' => $otherUser->email,
        ])->assertSessionHasErrors([
            'email' => 'The email address has already been taken.',
        ]);

        // Test password min length message
        put(route('dashboard.settings.update'), [
            'name' => $currentUser->name,
            'username' => $currentUser->username,
            'email' => $currentUser->email,
            'password' => '1234567',
        ])->assertSessionHasErrors([
            'password' => 'The password must be at least 8 characters.',
        ]);
    });
});

describe('Setting Authentication', function () {
    it('requires authentication to access settings', function () {
        // Logout first
        auth()->logout();

        get(route('dashboard.settings.index'))
            ->assertRedirect('/login'); // Adjust based on your auth middleware redirect
    });

    it('requires authentication to update settings', function () {
        // Logout first
        auth()->logout();

        put(route('dashboard.settings.update'), [
            'name' => 'Test Name',
            'username' => 'test_username',
            'email' => 'test@example.com',
        ])->assertRedirect('/login'); // Adjust based on your auth middleware redirect
    });
});
