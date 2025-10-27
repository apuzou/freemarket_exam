<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\CustomVerifyEmail;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    // 会員登録後、認証メールが送信される
    public function test_verification_email_sent_after_registration()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        
        Notification::assertSentTo(
            $user,
            CustomVerifyEmail::class
        );
    }

    // メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する
    public function test_verification_button_redirects_to_verification_site()
    {
        $user = User::factory()->unverified()->create();
        session(['pending_verification_user_id' => $user->id]);

        $response = $this->post('/email/resend');

        $response->assertStatus(302);
    }

    // メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する
    public function test_unverified_user_can_access_home()
    {
        $user = User::factory()->unverified()->create();
        
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }
}
