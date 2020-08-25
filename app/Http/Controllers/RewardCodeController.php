<?php

namespace App\Http\Controllers;

use App\RewardCode;
use App\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:User', ['only' => ['redeemCode']]);
        $this->middleware('role:Admin');
    }

    protected function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function index() {
        return RewardCode::all();
    }

    public function generateRandomCode(Request $request, $length, $reward) {

        $counter = 0;

        do {
            $code = generateRandomString($length);
            $exists = RewardCode::where('unique_code', $code);
            $counter += 1;
        } while ($exists && $counter < 10);

        if($counter >= 10) {
            return response()->make("Error, couldn't generate random code even after 10 tries", 400);
        }

        // Code is generated
        return response()->json(RewardCode::insert([
            'unique_code' => $code,
            'reward' => $reward
            ]), 201);
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
