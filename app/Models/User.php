<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Conversations where user is sender
    public function sentConversations()
    {
        return $this->hasMany(Conversation::class, 'sender_id');
    }

    // Conversations where user is receiver
    public function receivedConversations()
    {
        return $this->hasMany(Conversation::class, 'receiver_id');
    }

    // All conversations for the user (sent or received)
    public function conversations()
    {
        // Use a query to get all conversations where user is either sender or receiver
        // and apply the not deleted scope
        return Conversation::where(function($query) {
            $query->where('sender_id', $this->id)
                  ->orWhere('receiver_id', $this->id);
        })->whereNotDeleted();
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.'.$this->id;
    }
}