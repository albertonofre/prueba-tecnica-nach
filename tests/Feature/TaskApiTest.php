<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Laravel\Sanctum\Sanctum;

class TaskApiTest extends TestCase
{
    public function test_can_create_user_and_get_token(): void
    {
        $response = $this->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['user', 'token'],
            ]);
    }

    public function test_can_list_users(): void
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'email', 'created_at'],
                ],
            ]);
    }

    public function test_can_create_task_for_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/users/{$user->id}/tasks", [
            'title' => 'Test Task',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'user_id', 'title', 'description', 'completed'],
            ]);
    }

    public function test_can_list_user_tasks(): void
    {
        $user = User::factory()->create();
        Task::factory()->count(3)->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/users/{$user->id}/tasks");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'user_id', 'title', 'description', 'completed'],
                ],
            ]);
    }

    public function test_can_complete_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'completed' => false]);
        Sanctum::actingAs($user);

        $response = $this->patchJson("/api/users/{$user->id}/tasks/{$task->id}/complete");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'user_id', 'title', 'description', 'completed', 'created_at', 'updated_at'],
            ])
            ->assertJsonPath('data.completed', true);

        $this->assertTrue($task->fresh()->completed);
    }

    public function test_can_delete_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/users/{$user->id}/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
            ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_can_filter_tasks_by_completed(): void
    {
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id, 'completed' => true]);
        Task::factory()->create(['user_id' => $user->id, 'completed' => false]);
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/users/{$user->id}/tasks?completed=true");

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertTrue($data[0]['completed']);
    }

    public function test_can_order_tasks(): void
    {
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id, 'title' => 'A Task']);
        Task::factory()->create(['user_id' => $user->id, 'title' => 'B Task']);
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/users/{$user->id}/tasks?order_by=title&order_direction=asc");

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals('A Task', $data[0]['title']);
    }

    public function test_authenticated_user_can_manage_other_user_tasks(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user1->id]);
        Sanctum::actingAs($user2);

        $response = $this->putJson("/api/users/{$user1->id}/tasks/{$task->id}", [
            'title' => 'Updated Title',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Updated Title');
    }

    public function test_authenticated_user_can_view_other_user_tasks(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user1->id, 'title' => 'Visible Task']);
        Sanctum::actingAs($user2);

        $response = $this->getJson("/api/users/{$user1->id}/tasks");

        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Visible Task');
    }

    public function test_tasks_require_authentication(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}/tasks");

        $response->assertStatus(401);
    }

    public function test_can_login_and_get_token(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['user', 'token'],
            ]);
    }

    public function test_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nobody@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }

    public function test_can_get_authenticated_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_nonexistent_user_returns_404(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/users/99999/tasks');

        $response->assertStatus(404);
    }

    public function test_can_logout_and_token_is_revoked(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/logout')
            ->assertStatus(200);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}