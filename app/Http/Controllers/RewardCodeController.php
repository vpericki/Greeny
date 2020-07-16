<?php

namespace App\Http\Controllers;

use App\RewardCode;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardCodeController extends Controller
{
    public function __construct()
    {
        //$this->middleware('role:User', ['only' => ['redeemCode']]);
        //$this->middleware('role:Admin');
    }

    public function index() {
        return RewardCode::all();
    }

    public function redeemCode($code) {

        $rewardCode = RewardCode::where('unique_code', $code);

        if($rewardCode) {
            $reward = $rewardCode->reward;

            Auth::user()->points = Auth::user()->points + $reward;

            return response()->json(['points' => Auth::user()->points], 200);
        }
        return response()->make('error, wrong code', 400);
    }
}
