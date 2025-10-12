<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;

class AdminLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    public function test_admin_can_login()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->get('/admin/login');
        $response->assertStatus(200);

        $data = [
            'email' => 'admin@example.com',
            'password' => 'password',
        ];

        $response = $this->post('/admin/login', $data);
        $this->actingAs($user);
    }

    public function test_admin_login_when_not_input_data_on_mail_column()
    {
        
    }

    public function test_admin_login_when_not_input_data_on_password_column()
    {

    }

    public function test_admin_login_when_invalid_data()
    {

    }
}