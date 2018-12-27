<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Sport\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Filter player data based on team and return it as team data with players inside it.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function players(Team $team): JsonResponse
    {
        $team->load('players');

        return response()->json($team);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $teams = Team::orderBy('name')
            ->withCount('players')
            ->paginate();

        return response()->json($teams);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
        ]);

        $team = Team::create($request->all());

        return response()->json($team);
    }

    /**
     * Display the specified resource.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function show(Team $team): JsonResponse
    {
        return response()->json($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Team $team
     * @return void
     */
    public function update(Request $request, Team $team)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
        ]);

        $team->fill($request->all());
        $team->save();

        return response()->json($team);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Team $team
     * @return JsonResponse
     */
    public function destroy(Team $team): JsonResponse
    {
        $team->delete();

        return response()->json($team);
    }
}
