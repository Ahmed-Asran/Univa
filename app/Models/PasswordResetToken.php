<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'token_id';
    public $timestamps = true; // because you have created_at
    const UPDATED_AT = null;
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    // Relation to user (if needed)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
