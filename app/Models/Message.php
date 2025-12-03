<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];


    protected $dates = ['read_at', 'receiver_deleted_at','sender_deleted_at'];

    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }
    public function isRead():bool
    {
        return $this->read_at != null;
    }
    
}
