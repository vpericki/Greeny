<?php

namespace App\Http\Controllers;

use App\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:User');
        $this->middleware('role:Admin', ['only' => ['store', 'update', 'delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Achievement::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'required_points' => ['required', 'integer', 'gt:0']
        ]);

        $achievement = $request->only('name', 'description', 'required_points');

        return response()->json(Achievement::create($achievement), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if($achievement = Achievement::where('id', $id)->first()) {
            return response()->json([$achievement, $achievement->users], 200);
        }

        return response()->json(['message' => 'Achievement not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'required_points' => ['required', 'integer', 'gt:0']
        ]);


        $achievement = Achievement::where('id', $id);

        $achievement->name = $request->name;
        $achievement->description = $request->description;
        $achievement->required_points = $request->required_points;

        $achievement->save();

        return response()->json($achievement, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $achievement = Achievement::where('id', $id);

        $achievement->delete();

        return response('deleted', 204);
    }
}
