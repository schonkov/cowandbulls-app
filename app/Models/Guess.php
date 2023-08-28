<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guess extends Model
{
    use HasFactory;

    //game_id - ok
    //user_id; - ok
    //user_guess (1234)
    
    //cows 0,1,2,3,4
    //bulls 0,1,2,3,4
    //score 0-100%;

    protected $table = 'guesses';


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

}
