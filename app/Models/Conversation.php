<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $guarded =[];

    public function messages(){
        return $this->hasMany(Message::class);
    }
    public function GetReceiver(){
        if($this->sender_id === auth()->id()){
              return User::firstWhere('id',operator: $this->receiver_id);
        }
        else{
              return User::firstWhere('id',operator: $this->sender_id);

        }
    }
}
