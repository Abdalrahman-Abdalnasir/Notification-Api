<?php

namespace App\tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $sender;
    protected User $recipient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sender = User::factory()->create();
        $this->recipient = User::factory()->create();

        Notification::fake();
    }

    public function test_authenticated_user_can_send_notification()
    {
        $response = $this->actingAs($this->sender)
            ->postJson('/api/notifications', [
                'user_id' => $this->recipient->id,
                'message' => 'Test message'
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'message']);

        Notification::assertSentTo(
            [$this->recipient],
            GeneralNotification::class
        );
    }

    public function test_unauthenticated_user_cannot_send_notification()
    {
        $response = $this->postJson('/api/notifications', [
            'user_id' => $this->recipient->id,
            'message' => 'Test message'
        ]);

        $response->assertStatus(401);
    }

    public function test_rate_limiting_works()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($this->sender)
                ->postJson('/api/notifications', [
                    'user_id' => $this->recipient->id,
                    'message' => "Test message $i"
                ]);
        }

        $response = $this->actingAs($this->sender)
            ->postJson('/api/notifications', [
                'user_id' => $this->recipient->id,
                'message' => 'Rate limited message'
            ]);

        $response->assertStatus(429);
    }

    public function test_invalid_input_fails_validation()
    {
        $response = $this->actingAs($this->sender)
            ->postJson('/api/notifications', [
                'user_id' => 999, // non-existent user
                'message' => ''
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id', 'message']);
    }
}
