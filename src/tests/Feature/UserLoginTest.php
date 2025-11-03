<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $this->actingAs($user);
        /*
        $data = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];
        $response = $this->post('/login', $data);  */

        $response = $this->get('/attendance');
        $response->assertStatus(200);
    }

    public function test_user_login_without_input_data_on_mail_column()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $data = [
            'email' => '',
            'password' => 'password',
        ];

        $response = $this->post('/login', $data);
        $response->assertValid([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    public function test_user_login_without_input_data_on_password_column()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $data = [
            'email' => 'test@example.com',
            'password' => '',
        ];

        $response = $this->post('/login', $data);
        $response->assertValid([
            'password' => 'パスワードを入力してください',
        ]);
    }

    public function test_user_login_with_invalid_data()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $data = [
            'email' => 'example@example.com',
            'password' => 'example0813',
        ];

        $response = $this->post('/login', $data);
        $response->assertValid([
            'email' => 'ログイン情報が登録されていません'
        ]);
    }
}
