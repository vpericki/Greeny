<?php

namespace App\Http\Controllers;

use App\RewardCode;
use App\User;
use Carbon\Exceptions\InvalidTypeException;
use Dotenv\Exception\ValidationException;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

class RewardCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:User', ['only' => ['redeemCode']]);
        $this->middleware('role:Admin');
    }

    public function index() {
        return RewardCode::all();
    }

    public function generateRandomCode(Request $request, $length, $reward) {

        if(!is_numeric($length) || !is_numeric($reward)) {
            return response()->make("Error, wrong parameter types, length and reward must be integers!", 400);
        }

        $counter = 0;

        do {
            $code = generateRandomString($length);
            $counter += 1;

            $exists = false;
            $exists = RewardCode::where('unique_code', $code)->exists();

            if(!$exists) {
                break;
            }
        } while ($counter < 10);

        if($counter >= 10) {
            return response()->make("Error, couldn't generate random code even after 10 tries", 400);
        }

        $rewardCode = new RewardCode;

        $rewardCode->unique_code = $code;
        $rewardCode->reward = $reward;

        $rewardCode->save();

        // Code is generated
        return response()->json($rewardCode, 201);
    }

    public function redeemCode(Request $request, $code) {

        $rewardCode = RewardCode::where('unique_code', $code);

        if($rewardCode) {
            $reward = $rewardCode->reward;

            Auth::user()->points = Auth::user()->points + $reward;

            return response()->json(['points' => Auth::user()->points], 200);
        }
        return response()->make('error, wrong code', 400);
    }


}
