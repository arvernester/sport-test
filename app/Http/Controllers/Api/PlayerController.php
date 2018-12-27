<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Sport\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $players = Player::with('team')
            ->orderBy('first_name')
            ->paginate();

        return response()->json($players);
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
            'team_id' => 'required|integer|exists:teams,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
        ]);

        $player = Player::create($request->all());

        return response()->json($player);
    }

    /**
     * Display the specified resource.
     *
     * @param Player $player
     * @return JsonResponse
     */
    public function show(Player $player): JsonResponse
    {
        $player->load('team');

        return response()->json($player);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Player $player
     * @return JsonResponse
     */
    public function update(Request $request, Player $player): JsonResponse
    {
        $this->validate($request, [
            'team_id' => 'required|integer|exists:teams,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
        ]);

        $player->fill($request->all());
        $player->save();

        return response()->json($player);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Player $player
     * @return JsonResponse
     */
    public function destroy(Player $player): JsonResponse
    {
        $player->delete();

        return response()->json($player);
    }
}
