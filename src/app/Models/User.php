<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public function hasVerifiedEmail()
    {
        return $this->email_verified_at !== null;
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'verification_code',
        'verification_code_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_code_expires_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Item::class, 'likes');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function hasCompletedProfile()
    {
        return $this->profile && isset($this->profile->postal_code) && $this->profile->postal_code !== '' && isset($this->profile->address) && $this->profile->address !== '';
    }

    public function generateVerificationCode()
    {
        // 6桁のランダムな数字を生成
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $this->verification_code = Hash::make($code);
        $this->verification_code_expires_at = now()->addMinutes(10); // 10分間有効
        $this->save();

        return $code; // ハッシュ化する前のコードを返す
    }

    public function sendEmailVerificationNotification()
    {
        $code = $this->generateVerificationCode();
        $this->notify(new \App\Notifications\CustomVerifyEmail($code));
    }
}
