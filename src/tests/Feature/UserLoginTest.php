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
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $this->actingAs($user);
    }

    public function test_user_login_when_not_input_data_on_mail_column()
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
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    public function test_user_login_when_not_input_data_on_password_column()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $data = [
            'email' => 'test@example.com',
            'password' => '',
        ];

        $response = $this->post('/login', $data);
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    public function test_user_login_when_invalid_data()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $data = [
            'email' => 'example@example.com',
            'password' => 'example0813',
        ];

        $response = $this->post('/login', $data);
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません'
        ]);
    }
}
