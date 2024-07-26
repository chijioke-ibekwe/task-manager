<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

it('registers a user on the application', function () {

    $this->post('api/auth/registration', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@task.com',
        'password' => 'password'
    ])
        ->assertStatus(201)
        ->assertJsonFragment([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@task.com'
        ]);

    expect(User::all())->toHaveCount(1);
});

it('thows a 422 exception when attempting to register a user with an already existing email on the application', function () {
    User::factory()->create([
        'email' => 'john.doe@task.com'
    ]);

    $this->post('api/auth/registration', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@task.com',
        'password' => 'password'
    ])
        ->assertStatus(422)
        ->assertJsonFragment([
            'message' => 'The email has already been taken.'
        ]);
});

it('thows a 422 exception when attempting to register with a password less than 8 characters', function () {
    
    $this->post('api/auth/registration', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@task.com',
        'password' => 'box'
    ])
        ->assertStatus(422)
        ->assertJsonFragment([
            'message' => 'The password field must be at least 8 characters.'
        ]);
});

it('successfully logs in a user with valid credentials', function () {
    User::factory()->create([
        'email' => 'john.doe@task.com',
        'password' => Hash::make('password')
    ]);

    $this->post('api/auth/login', [
        'email' => 'john.doe@task.com',
        'password' => 'password'
    ])
        ->assertOK()
        ->assertJsonFragment([
            'message' => 'User logged in successfully',
            'token_type' => 'bearer'
        ]);
});

it('throws a 401 exception when attempting to login with invalid credentials', function () {
    User::factory()->create([
        'email' => 'john.doe@task.com',
        'password' => Hash::make('password')
    ]);

    $this->post('api/auth/login', [
        'email' => 'john.doe@task.com',
        'password' => 'passport'
    ])
        ->assertStatus(401)
        ->assertJsonFragment([
            'message' => 'Invalid username or password'
        ]);
});

it('logs out a user usccessfully', function () {
    $user = User::factory()->create([
        'email' => 'john.doe@task.com',
        'password' => Hash::make('password')
    ]);

    $token = Auth::login($user);
    
    $this->withToken($token)->get('api/auth/logout')
        ->assertOK()
        ->assertJsonFragment([
            'message' => 'User logged out successfully'
        ]);
});