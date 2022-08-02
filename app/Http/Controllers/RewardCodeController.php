<?php

namespace App\Http\Controllers;

use AchievementChecker;
use App\RewardCode;
use App\User;
use Carbon\Exceptions\InvalidTypeException;
use Dotenv\Exception\ValidationException;
use Exception;
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

        if(!is_numeric($reward) || $reward <= 0 || $reward > 100) {
            return response()->make("Error, reward must be between 1 and 100 (included)", 400);
        }

        if(!is_numeric($length) || $length < 6 || $length > 16) {
            return response()->make("Error, length must be between 6 and 16 (included)", 400);
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

    public function generateCode(Request $request, $reward) {

        $request->validate([
            'code' => ['required', "unique:reward_codes,unique_code", 'min:6']
        ]);

        if(!is_numeric($reward)) {
            return response()->make("Error, wrong parameter types, length and reward must be integers!", 400);
        }

        $code = new RewardCode;
        $code->unique_code = $request->code;
        $code->reward = $reward;

        $code->save();

        return response()->json($code, 201);
    }

    public function redeemCode(Request $request, $code) {

        $rewardCode = RewardCode::where('unique_code', $code)->first();

        if($rewardCode === null) {
            return response()->make("Entered code doesn't exist", 400);
        }

        $user = User::where('id', Auth::user()->id)->first();

        $user->points = 0;
        $codeNotUsed = true;

        foreach($user->rewardCodes as $c) {
            $user->points += $c->reward;

            if ($c->id === $rewardCode->id) {
                $codeNotUsed = false;
            }
            //Log::debug('Message', [$c, $rewardCode]);


        }

        if ($codeNotUsed) {
            $achievementChecker = new AchievementChecker;

            $user->points += $rewardCode->points;
            $user->rewardCodes()->attach($rewardCode);

            $achievementChecker->checkAndUpdateForUser($user);

        }
        else {
            return response()->make('Error, code already used!', 400);
        }

        try {
            $user->save();
            return response()->make('Successfully redeemed a code', 200);
        }
        catch (Exception $exception) {

            return response()->json(['error' => json_encode($exception)], 500);
        }
    }

    public function delete(Request $request, $id) {

        if(!is_numeric($id)) {
            return response()->make('Id is not a number', 400);
        }

        $rewardCode = RewardCode::where('id', $id)->first();

        try {
            $rewardCode->delete();
            return response()->make("Successfully deleted code with id $id!", 204);

        }
        catch (Exception $exception) {

            return response()->json(['error' => json_encode($exception)], 500);
        }


    }


}
