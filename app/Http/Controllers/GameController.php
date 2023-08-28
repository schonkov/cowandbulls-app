<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Guess;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

class GameController extends Controller
{
    //
    public function index()
    {
        $current_user = Auth::user();
        $current_user_id = $current_user->id;
        $games = Game::where('user_id', $current_user_id)->get()->sortDesc(); //all games for user
        $guesses_sum_score = Guess::where('user_id', $current_user_id)->sum('score');
        $guesses_avg_score = Guess::where('user_id', $current_user_id)->avg('score');
        return view('game.index', ['games' => $games, 'current_user' => $current_user, 'guesses_sum_score' => $guesses_sum_score, 'guesses_avg_score' => $guesses_avg_score]);
    }

    public function show($id)
    {
        $current_user = Auth::user();
        $current_user_id = $current_user->id;
        // dd($id);
        // $id = $id * 1;
        $games = Game::where('user_id', $current_user_id)->get()->sortDesc(); //all games for user
        $current_game = Game::where('user_id', $current_user_id)->where('id', $id)->first(); //game
        if (!$current_game) {
            return redirect()->route('game.index');
        }
        $guesses_sum_score = Guess::where('user_id', $current_user_id)->where('game_id', $id)->sum('score');
        $guesses_avg_score = Guess::where('user_id', $current_user_id)->where('game_id', $id)->avg('score');
        return view('game.show', ['id' => $id, 'games' => $games, 'current_game' => $current_game, 'current_user' => $current_user, 'guesses_sum_score' => $guesses_sum_score, 'guesses_avg_score' => $guesses_avg_score]);
    }

    public function endGame(Request $request)
    {
        $input = $request->all();

        $id = $request->input('id');

        $current_user = Auth::user();
        $current_user_id = $current_user->id;

        // $id = $id * 1;
        $current_game = Game::where('user_id', $current_user_id)->where('id', $id)->first(); //game
        $success = "I am sorry you gave up.";
        if ($current_game) {
            $current_game->ended = 1;
            $current_game->success = 0;
            $current_game->save();
            //   dd($current_game);

        }
        // else
        // {
        //     $success = "Not founded the game.";
        // }


        Log::info($input);

        return response()->json(['success' => $success]);
    }

    public function generateRandomNumber() //custom 4 digits unique number; 4271 
    {
        $digits_count = 4;
        $generated_digits = "";
        $log_messages = []; //log messages in storage/logs/larave.log (for debug)

        for ($i = 0; $i < $digits_count; $i++) {
            $log_messages[] = "----------------";
            $log_messages[] = "<br/> position" . $i;
            $log_messages[] = " generated_digits=" . $generated_digits;

            $this_position_digit = "";
            if (!empty($generated_digits[$i])) {
                $this_position_digit = $generated_digits[$i];
                $log_messages[] = "this_position_digit=" . $this_position_digit;
                $log_messages[] = "skip, because is not empty";
                continue;
            }

            do {
                $current_digit = rand(0, 9); //min, max;

                $is_correct_digit = false;
                $is_added_digit = false;
                $to_add_digit = "";
                //validation
                $log_messages[] = ' -try with digit ' . $current_digit;

                //1. 4 unique digits
                if (str_contains($generated_digits, $current_digit)) {
                    $log_messages[] = "-- 1. not unique digit $current_digit in $generated_digits ";
                    continue; //next try again
                }

                //2. - if in use, digits 1 and 8 should be right next to each other
                if (($current_digit == '1') || ($current_digit == '8')) {
                    $log_messages[] = "current digit 1 or 8";
                    if ($current_digit == '1' && $i + 2 <= $digits_count) {
                        $log_messages[] =  "if current digit 1";

                        $to_add_digit = "18";
                        $is_correct_digit = true;
                        $is_added_digit = false;
                    } else if ($current_digit == '8' && $i + 2 <= $digits_count) {
                        $log_messages[] =  "if current digit 8";
                        $to_add_digit = "81";
                        $is_correct_digit = true;
                        $is_added_digit = false;
                    } else {
                        continue; //no space for 2 digits 
                    }
                }

                //3. - if in use, digits 4 and 5 shouldn't be on even index / position
                //even = 0,2,4
                if (($current_digit == '4') || ($current_digit == '5')) {
                    if ($i % 2 == 0) //even 0,2,4
                    {
                        $log_messages[] =  "skip, because is not on odd position";
                        continue;
                    } else //odd 1,3,5
                    {
                        $is_correct_digit = true;
                        $is_added_digit = false;
                        $to_add_digit = $current_digit;
                    }
                }


                if (!$to_add_digit) {
                    $log_messages[] =  "last if";
                    $is_correct_digit = true;
                    $is_added_digit = false;
                    $to_add_digit = $current_digit;
                }
            } while (!$is_correct_digit);

            if (!$is_added_digit) {
                $generated_digits .= $to_add_digit;
                $log_messages[] =  '<br/> -adding digit/s ' . $to_add_digit;
            }
        }

        $log_messages[] =  "generated_digits" . $generated_digits;
        $log_messages[] =  "---------------";
        Log::info($log_messages);

        return $generated_digits;
    }
    public function newGame(Request $request)
    {
        $current_user = Auth::user();
        $current_user_id = $current_user->id;

        $new_game = new Game();
        $new_game->user_id = $current_user_id;
        //generate answer
        $generated_digits = $this->generateRandomNumber();
        // dd($generated_digits);
        $new_game->answer = $generated_digits;
        $new_game->save();

        if ($request->isMethod('post')) {
            return response()->json(['success' => true, 'new_game_id' => $new_game->id]);
        }
        return redirect()->route('game.show', ['id' => $new_game->id]);
    }

    public function calculateCowsAndBulls($user_guess, $answer)
    {
        $user_guess = strval($user_guess);
        $answer = strval($answer);
        $response = [];
        $cows = 0;
        $bulls = 0;

        $cows_arr = [];
        $bulls_arr = [];
        $response_sentence = "";
        $log_messages = [];
        $log_messages[] = "----------------";
        $log_messages[] = "user_guess=" . $user_guess;
        $log_messages[] = "answer=" . $answer;

        for ($i = 0; $i < strlen($user_guess); $i++) {
            $guess_position_digit = $user_guess[$i];
            $log_messages[] = "position[i]" . $i;
            $log_messages[] = "guess_position_digit=" . $guess_position_digit;

            for ($j = 0; $j < strlen($answer); $j++) {
                $answer_position_digit = $answer[$j];
                $log_messages[] = "position[j]" . $j;
                $log_messages[] = "answer_position_digit=" . $answer_position_digit;

                if ($i == $j && $guess_position_digit == $answer_position_digit && !in_array($answer_position_digit, $bulls_arr)) {
                    $bulls++;
                    $log_messages[] = "bulls++ => " . $bulls;
                    $bulls_arr[] = $answer_position_digit;
                    // continue;
                } else if ($guess_position_digit == $answer_position_digit && !in_array($answer_position_digit, $cows_arr)) {
                    $cows++;
                    $log_messages[] = "cows++ => " . $cows;
                    $cows_arr[] = $answer_position_digit;
                }
            }
        }

        $response['cows'] = $cows;
        $response['bulls'] = $bulls;
        $response['score'] = ($cows * 1) * 10 + ($bulls * 1) * 25;

        $response['cows_arr'] = $cows_arr;
        $response['bulls_arr'] = $bulls_arr;

        $response['ended'] = '0';
        $response['success'] = '0';

        if ($bulls == strlen($answer)) {
            $response_sentence = "Bravo. Your guess is the exact answer. ";
            $response['ended'] = '1';
            $response['success'] = '1';
        } else {
            $response_sentence = "You have $bulls bull(s) and $cows cow(s).";
        }
        $response['response_sentence'] = $response_sentence;
        $log_messages[] =  "---------------";
        Log::info($log_messages);
        return $response;
    }

    public function userGuess($id, Request $request)
    {
        $current_user = Auth::user();
        $current_user_id = $current_user->id;

        $current_user_guess = $request->input('user_guess');
        $is_unique_digits_only = count(array_unique(str_split($current_user_guess)));

        //maybe validator?
        if (($is_unique_digits_only !== strlen($current_user_guess)) || (strlen($current_user_guess) != 4)) {
            return response()->json(['success' => false,  "response_sentence" => "Your number is wrong. Number should be with  4 unique digits only"]);
        }
        // dd($is_unique_digits_only);

        // $id = $id * 1;
        $current_game = Game::where('user_id', $current_user_id)->where('id', $id)->first(); //game
        $the_answer = $current_game->answer;

        $response = $this->calculateCowsAndBulls($current_user_guess, $the_answer);
        // dd($response);

        if ($response) {
            $new_guess = new Guess();
            $new_guess->user_id = $current_user_id;
            $new_guess->game_id = $current_game->id;
            $new_guess->user_guess = $current_user_guess;
            $new_guess->bulls = $response["bulls"];
            $new_guess->cows =  $response["cows"];
            $new_guess->score = $response["score"];
            $new_guess->response_sentence = $response["response_sentence"];
            $new_guess->save();

            $current_game->ended = $response["ended"];
            $current_game->success = $response["success"];
            $current_game->save();
        }


        return response()->json(['success' => true, 'new_guess' => $new_guess, 'current_game' => $current_game,  "response_sentence" => $response["response_sentence"]]);
    }

    public function topPlayers()
    {
        $current_user = Auth::user();
        $current_user_id = $current_user->id;

        $top_players = DB::select('SELECT user_id, AVG(score) as avg_score, SUM(score) as sum_score FROM `guesses` 
        GROUP BY user_id  ORDER BY sum_score DESC LIMIt 10;');

        foreach ($top_players as $index => $player) {
            $top_players[$index]->user = User::where('id', $player->user_id)->first();
        }
        // dd($top_players);
        return view('game.topplayers', ['top_players' => $top_players, 'current_user' => $current_user]);
    }
}
