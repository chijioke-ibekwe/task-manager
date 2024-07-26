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
            'due_date' => Carbon::parse('2024-12-09'),
            'status' => TaskStatus::INCOMPLETE,
            'user_id' => $user->id
        ]);
});

// it('throws a 404 when attempting to fetch a task belonging to another user', function () {
//     $tenant_one = Tenant::factory()->create();
//     $tenant_two = Tenant::factory()->create();

//     $user = User::factory()->create([
//         'tenant_id' => $tenant_two
//     ]);

//     $product = Product::factory()->createOneQuietly([
//         'product_name' => 'Bags of Rice',
//         'price' => 40000,
//         'currency' => 'NGN',
//         'description' => 'Quality bags of rice manufactured by caprice',
//         'stock_quantity' => 1000,
//         'maximum_lead_time' => 7,
//         'minimum_lead_time' => 14,
//         'payment_terms' => 30,
//         'status' => Unpublished::class,
//         'tenant_id' => $tenant_one->id
//     ]);

//     $this->actingAs($user)->get("api/v1/products/$product->id")
//         ->assertStatus(404);
// });

// it('creates a supplier product', function () {
//     $tenant = Tenant::factory()->create();
//     $user = User::factory()->create([
//         'tenant_id' => $tenant->id
//     ]);

//     $image = UploadedFile::fake()->image('image.jpg');

//     $this->actingAs($user)->post('/api/v1/products', [
//         'product_name' => 'Bags of Rice',
//         'price' => 40000,
//         'currency' => 'NGN',
//         'description' => 'Quality bags of rice manufactured by caprice',
//         'stock_quantity' => 1000,
//         'maximum_lead_time' => 7,
//         'minimum_lead_time' => 14,
//         'payment_terms' => 30,
//         'image' => $image
//     ])
//         ->assertOk()
//         ->assertJsonFragment([
//             'product_name' => 'Bags of Rice',
//             'price' => 40000,
//             'currency' => 'NGN',
//             'description' => 'Quality bags of rice manufactured by caprice',
//             'stock_quantity' => 1000,
//             'maximum_lead_time' => 7,
//             'minimum_lead_time' => 14,
//             'payment_terms' => 30,
//             'status' => Unpublished::class,
//             'tenant_id' => $tenant->id
//         ])
//         ->assertJsonPath('data.documents.product_image.0.file_name', 'image.jpg');
// });

// it('updates a supplier product', function () {
//     $tenant = Tenant::factory()->create();
//     $user = User::factory()->create([
//         'tenant_id' => $tenant->id
//     ]);

//     $product = Product::factory()->createOneQuietly([
//         'product_name' => 'Bags of Rice',
//         'price' => 40000,
//         'currency' => 'NGN',
//         'description' => 'Quality bags of rice manufactured by caprice',
//         'stock_quantity' => 1000,
//         'maximum_lead_time' => 7,
//         'minimum_lead_time' => 14,
//         'payment_terms' => 30,
//         'status' => Published::class,
//         'tenant_id' => $tenant->id
//     ]);

//     $image = UploadedFile::fake()->image('image_two.jpg');

//     $this->actingAs($user)->patch("/api/v1/products/$product->id", [
//         'product_name' => 'Bags of Foreign Rice',
//         'price' => 10000,
//         'currency' => 'GHS',
//         'image' => $image
//     ])
//         ->assertOk()
//         ->assertJsonFragment([
//             'product_name' => 'Bags of Foreign Rice',
//             'price' => 10000,
//             'currency' => 'GHS',
//             'description' => 'Quality bags of rice manufactured by caprice',
//             'stock_quantity' => 1000,
//             'maximum_lead_time' => 7,
//             'minimum_lead_time' => 14,
//             'payment_terms' => 30,
//             'status' => UnderReview::class,
//             'tenant_id' => $tenant->id
//         ])
//         ->assertJsonPath('data.documents.product_image.0.file_name', 'image_two.jpg');
// });

// it('updates the status of a supplier product', function () {
//     $tenant = Tenant::factory()->create();
//     $user = User::factory()->create([
//         'tenant_id' => $tenant->id
//     ]);

//     $product = Product::factory()->createOneQuietly([
//         'product_name' => 'Bags of Rice',
//         'price' => 40000,
//         'currency' => 'NGN',
//         'description' => 'Quality bags of rice manufactured by caprice',
//         'stock_quantity' => 1000,
//         'maximum_lead_time' => 7,
//         'minimum_lead_time' => 14,
//         'payment_terms' => 30,
//         'status' => Unpublished::class,
//         'tenant_id' => $tenant->id
//     ]);

//     $image = UploadedFile::fake()->image('image_two.jpg');
//     $product->addMedia($image)->toMediaCollection(ProductEnum::PRODUCT_IMAGE->value);

//     $this->actingAs($user)->patch("/api/v1/products/$product->id/status", [
//         'status' => 'under_review'
//     ])
//         ->assertOk()
//         ->assertJsonFragment([
//             'product_name' => 'Bags of Rice',
//             'price' => 40000,
//             'currency' => 'NGN',
//             'description' => 'Quality bags of rice manufactured by caprice',
//             'stock_quantity' => 1000,
//             'maximum_lead_time' => 7,
//             'minimum_lead_time' => 14,
//             'payment_terms' => 30,
//             'status' => UnderReview::class,
//             'tenant_id' => $tenant->id
//         ])
//         ->assertJsonPath('data.documents.product_image.0.file_name', 'image_two.jpg');
// });

// it('throws a 400 when updating the status of a supplier product to the same status', function () {
//     $tenant = Tenant::factory()->create();
//     $user = User::factory()->create([
//         'tenant_id' => $tenant->id
//     ]);

//     $product = Product::factory()->createOneQuietly([
//         'status' => Unpublished::class,
//         'tenant_id' => $tenant->id
//     ]);

//     $this->actingAs($user)->patch("/api/v1/products/$product->id/status", [
//         'status' => 'unpublished'
//     ])
//         ->assertStatus(400)
//         ->assertJsonFragment([
//             'msg' => 'Product is already unpublished'
//         ]);
// });

// it('throws a 422 when trying to update the status of a supplier product to published', function () {
//     $tenant = Tenant::factory()->create();
//     $user = User::factory()->create([
//         'tenant_id' => $tenant->id
//     ]);

//     $product = Product::factory()->createOneQuietly([
//         'tenant_id' => $tenant->id
//     ]);

//     $this->actingAs($user)->patch("/api/v1/products/$product->id/status", [
//         'status' => 'published'
//     ])
//         ->assertStatus(422);
// });

// it('deletes a supplier product', function () {
//     $tenant = Tenant::factory()->create();
//     $user = User::factory()->create([
//         'tenant_id' => $tenant->id
//     ]);

//     $product = Product::factory()->createOneQuietly([
//         'tenant_id' => $tenant->id
//     ]);

//     $this->actingAs($user)->delete("/api/v1/products/$product->id")
//         ->assertOk()
//         ->assertJsonFragment([
//             'msg' => 'Product deleted successfully',
//         ]);
// });
