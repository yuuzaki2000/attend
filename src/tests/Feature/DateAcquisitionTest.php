<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class DateAcquisitionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    public function test_user_can_get_date()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $this->actingAs($user);

        $current_time = \Carbon\Carbon::now()->format('H:i');

        $response = $this->get('/attendance');
        $response->assertStatus(200);
        $response->assertSeeText($current_time);
    }
}