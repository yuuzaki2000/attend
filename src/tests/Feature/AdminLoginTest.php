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
        $this->actingAs($admin);
    }

    public function test_admin_login_without_input_data_on_mail_column()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => 'adminadmin',
        ]);

        $response = $this->get('/admin/login');
        $response->assertStatus(200);

        $data = [
            'email' => '',
            'password' => 'password',
        ];

        $response = $this->post('/admin/login', $data);
        $response->assertValid([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    public function test_admin_login_when_not_input_data_on_password_column()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => 'adminadmin',
        ]);

        $response = $this->get('/admin/login');
        $response->assertStatus(200);

        $data = [
            'email' => 'guest@gmail.com',
            'password' => 'adminadmin',
        ];

        $response = $this->post('/admin/login', $data);
        $response->assertValid([
            'email' => 'パスワードを入力してください'
        ]);
    }

    public function test_admin_login_when_invalid_data()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => 'adminadmin',
        ]);

        $response = $this->get('/admin/login');
        $response->assertStatus(200);

        $data = [
            'email' => 'guest@gmail.com',
            'password' => 'adminadmin',
        ];

        $response = $this->post('/admin/login', $data);
        $response->assertValid([
            'email' => 'ログイン情報が登録されていません'
        ]);
    }
}