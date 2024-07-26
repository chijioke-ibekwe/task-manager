<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;

it('fetches all tasks belonging to a user', function () {
    $user_one = User::factory()->create();
    $user_two = User::factory()->create();

    Task::factory()->count(5)->create([
        'user_id' => $user_one->id
    ]);
    
    Task::factory()->count(3)->create([
        'user_id' => $user_two->id
    ]);

    $response = $this->actingAs($user_one)->get('api/tasks')
        ->assertOk()
        ->assertJsonPath('data.data.0.user_id', $user_one->id)
        ->original['data'];

    expect($response)->toHaveCount(5);
});

it('fetches a single task belonging to user', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'description' => 'Go to the gym',
        'due_date' => Carbon::parse('2024-12-09'),
        'status' => TaskStatus::INCOMPLETE,
        'user_id' => $user->id
    ]);

    $this->actingAs($user)->get("api/tasks/$task->id")
        ->assertOk()
        ->assertJsonFragment([
            'description' => 'Go to the gym',
            'due_date' => '2024-12-09',
            'status' => 'incomplete',
            'user_id' => $user->id
        ]);
});

it('throws a 404 when attempting to fetch a task belonging to another user', function () {
    $user_one = User::factory()->create();
    $user_two = User::factory()->create();

    Task::factory()->create([
        'description' => 'Go to the gym',
        'due_date' => Carbon::parse('2024-12-09'),
        'status' => TaskStatus::INCOMPLETE,
        'user_id' => $user_one->id
    ]);

    $task = Task::factory()->create([
        'description' => 'Do my Laundry',
        'due_date' => Carbon::parse('2024-12-09'),
        'status' => TaskStatus::INCOMPLETE,
        'user_id' => $user_two->id
    ]);

    $this->actingAs($user_one)->get("api/tasks/$task->id")
        ->assertStatus(404)
        ->assertJsonFragment([
            'message' => 'Task not found'
        ]);        
});

it('creates a task', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/api/tasks', [
        'description' => 'Do my Laundry',
        'due_date' => '2024-12-09'
    ])
        ->assertStatus(201)
        ->assertJsonFragment([
            'description' => 'Do my Laundry',
            'due_date' => '2024-12-09',
            'status' => 'incomplete',
            'user_id' => $user->id
        ]);
});

it('updates a task belonging to a user', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'description' => 'Do my Laundry',
        'due_date' => Carbon::parse('2024-12-09'),
        'status' => TaskStatus::INCOMPLETE,
        'user_id' => $user->id
    ]);

    $this->actingAs($user)->put("/api/tasks/$task->id", [
        'description' => 'Do my Laundry',
        'due_date' => '2024-12-15'
    ])
        ->assertOk()
        ->assertJsonFragment([
            'description' => 'Do my Laundry',
            'due_date' => '2024-12-15',
            'status' => 'incomplete',
            'user_id' => $user->id
        ]);
});

it('throws a 422 exception when updating a task without the description parameter on the request body', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'description' => 'Do my Laundry',
        'due_date' => Carbon::parse('2024-12-09'),
        'status' => TaskStatus::INCOMPLETE,
        'user_id' => $user->id
    ]);

    $this->actingAs($user)->put("/api/tasks/$task->id", [
        'due_date' => '2024-12-15'
    ])
        ->assertStatus(422)
        ->assertJsonFragment([
            'message' => 'The description field is required.'
        ]);
});

it('marks the status of a task as complete', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'description' => 'Do my Laundry',
        'due_date' => Carbon::parse('2024-12-09'),
        'status' => TaskStatus::INCOMPLETE,
        'user_id' => $user->id
    ]);

    $this->actingAs($user)->patch("/api/tasks/$task->id/complete")
        ->assertOk()
        ->assertJsonFragment([
            'description' => 'Do my Laundry',
            'due_date' => '2024-12-09',
            'status' => 'complete',
            'user_id' => $user->id
        ]);
});

it('throws a 400 when attempting to mark an already completed task as complete', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'description' => 'Do my Laundry',
        'due_date' => Carbon::parse('2024-12-09'),
        'status' => TaskStatus::COMPLETE,
        'user_id' => $user->id
    ]);

    $this->actingAs($user)->patch("/api/tasks/$task->id/complete")
        ->assertStatus(400)
        ->assertJsonFragment([
            'message' => 'Task is already completed'
        ]);
});

it('deletes a task belonging to a user', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'description' => 'Do my Laundry',
        'due_date' => Carbon::parse('2024-12-09'),
        'status' => TaskStatus::COMPLETE,
        'user_id' => $user->id
    ]);

    $this->actingAs($user)->delete("/api/tasks/$task->id")
        ->assertOK()
        ->assertJsonFragment([
            'message' => 'Task deleted successfully'
        ]);

    expect(Task::count())->toBe(0);
});
