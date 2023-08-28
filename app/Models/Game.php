<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $table = 'games';


    //user_id; - ok
    //guesses; - ok
    //answer;
    //ended;
    //success;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guesses()
    {
        return $this->hasMany(Guess::class);
    }

}
