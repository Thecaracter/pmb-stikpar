<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\RegistrationWave;
use App\Models\RegistrationPath;
use App\Models\KipQuota;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;

class KipQuotaTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $wave;
    protected $kipPath;
    protected $regularPath;
    protected $kipQuota;

    protected function setUp(): void
    {
        parent::setUp();

        // Create necessary test data
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'role' => 'user'
        ]);

        $this->wave = RegistrationWave::create([
            'name' => 'Test Wave',
            'wave_number' => 1,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'administration_fee' => 100000,
            'registration_fee' => 500000,
            'is_active' => true,
            'available_paths' => [
                'kip' => true,
                'reg' => true
            ]
        ]);

        $this->kipPath = RegistrationPath::create([
            'name' => 'Jalur KIP',
            'code' => 'KIP',
            'description' => 'Test KIP path',
            'is_active' => true
        ]);

        $this->regularPath = RegistrationPath::create([
            'name' => 'Jalur Reguler',
            'code' => 'REG',
            'description' => 'Test regular path',
            'is_active' => true
        ]);

        $this->kipQuota = KipQuota::create([
            'year' => date('Y'),
            'total_quota' => 10,
            'remaining_quota' => 10,
            'is_active' => true
        ]);
    }

    /** @test */
    public function it_can_create_kip_registration_when_quota_available()
    {
        $this->withoutMiddleware();
        $this->actingAs($this->user);

        $response = $this->postJson('/registration/create', [
            'wave_id' => $this->wave->id,
            'path_id' => $this->kipPath->id
        ]);

        // Debug output
        if ($response->getStatusCode() !== 200) {
            dump($response->getContent());
        }

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'registration_number',
                'administration_fee',
                'wave_name',
                'path_name'
            ]
        ]);

        // Check that quota was decremented
        $this->kipQuota->refresh();
        $this->assertEquals(9, $this->kipQuota->remaining_quota);

        // Check that registration was created
        $this->assertDatabaseHas('registrations', [
            'user_id' => $this->user->id,
            'wave_id' => $this->wave->id,
            'path_id' => $this->kipPath->id,
            'status' => 'pending'
        ]);
    }

    /** @test */
    public function it_cannot_create_kip_registration_when_quota_exhausted()
    {
        // Set quota to 0
        $this->kipQuota->update(['remaining_quota' => 0]);

        $this->withoutMiddleware();
        $this->actingAs($this->user);

        $response = $this->postJson('/registration/create', [
            'wave_id' => $this->wave->id,
            'path_id' => $this->kipPath->id
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Kuota KIP untuk tahun ini sudah habis'
        ]);

        // Check that no registration was created
        $this->assertDatabaseMissing('registrations', [
            'user_id' => $this->user->id,
            'wave_id' => $this->wave->id,
            'path_id' => $this->kipPath->id
        ]);
    }

    /** @test */
    public function it_can_create_non_kip_registration_regardless_of_kip_quota()
    {
        // Set KIP quota to 0
        $this->kipQuota->update(['remaining_quota' => 0]);

        $this->withoutMiddleware();
        $this->actingAs($this->user);

        $response = $this->postJson('/registration/create', [
            'wave_id' => $this->wave->id,
            'path_id' => $this->regularPath->id
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data'
        ]);

        // Check that KIP quota was not affected
        $this->kipQuota->refresh();
        $this->assertEquals(0, $this->kipQuota->remaining_quota);

        // Check that registration was created
        $this->assertDatabaseHas('registrations', [
            'user_id' => $this->user->id,
            'wave_id' => $this->wave->id,
            'path_id' => $this->regularPath->id,
            'status' => 'pending'
        ]);
    }

    /** @test */
    public function it_restores_kip_quota_when_registration_is_cancelled()
    {
        // Create a KIP registration first
        $this->withoutMiddleware();
        $this->actingAs($this->user);
        $this->postJson('/registration/create', [
            'wave_id' => $this->wave->id,
            'path_id' => $this->kipPath->id
        ]);

        // Check quota was decremented
        $this->kipQuota->refresh();
        $this->assertEquals(9, $this->kipQuota->remaining_quota);

        // Get the registration
        $registration = Registration::where('user_id', $this->user->id)->first();

        // Cancel the registration
        $response = $this->deleteJson("/registration/{$registration->id}/cancel");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Pendaftaran berhasil dibatalkan'
        ]);

        // Check that quota was restored
        $this->kipQuota->refresh();
        $this->assertEquals(10, $this->kipQuota->remaining_quota);

        // Check that registration was deleted
        $this->assertDatabaseMissing('registrations', [
            'id' => $registration->id
        ]);
    }

    /** @test */
    public function it_handles_race_condition_when_multiple_users_register_simultaneously()
    {
        // Set quota to 1
        $this->kipQuota->update(['remaining_quota' => 1]);

        // Create another user
        $user2 = User::factory()->create([
            'email' => 'test2@example.com',
            'role' => 'user'
        ]);

        $this->withoutMiddleware();

        // Simulate concurrent requests
        $responses = [];

        DB::transaction(function () use (&$responses, $user2) {
            // First user tries to register
            $this->actingAs($this->user);
            $responses[0] = $this->postJson('/registration/create', [
                'wave_id' => $this->wave->id,
                'path_id' => $this->kipPath->id
            ]);

            // Second user tries to register (should fail due to quota)
            $this->actingAs($user2);
            $responses[1] = $this->postJson('/registration/create', [
                'wave_id' => $this->wave->id,
                'path_id' => $this->kipPath->id
            ]);
        });

        // One should succeed, one should fail
        $successCount = 0;
        $failCount = 0;

        foreach ($responses as $response) {
            if ($response->getStatusCode() === 200) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        $this->assertEquals(1, $successCount);
        $this->assertEquals(1, $failCount);

        // Check final quota
        $this->kipQuota->refresh();
        $this->assertEquals(0, $this->kipQuota->remaining_quota);
    }

    /** @test */
    public function it_does_not_restore_quota_when_cancelling_non_kip_registration()
    {
        // Create a regular registration
        $this->withoutMiddleware();
        $this->actingAs($this->user);
        $this->postJson('/registration/create', [
            'wave_id' => $this->wave->id,
            'path_id' => $this->regularPath->id
        ]);

        // Check quota was not affected
        $this->kipQuota->refresh();
        $this->assertEquals(10, $this->kipQuota->remaining_quota);

        // Get the registration
        $registration = Registration::where('user_id', $this->user->id)->first();

        // Cancel the registration
        $response = $this->deleteJson("/registration/{$registration->id}/cancel");

        $response->assertStatus(200);

        // Check that quota was not changed
        $this->kipQuota->refresh();
        $this->assertEquals(10, $this->kipQuota->remaining_quota);
    }
}
