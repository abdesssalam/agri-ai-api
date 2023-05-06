<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function insertHistory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'condition' => 'required|string|max:255',
            'img' => 'equired|string|max:255',
        ]);

        // Get the authenticated user ID
        $user_id = auth()->user()->id;



        // Create a new history instance
        $history = new History([
            'name' => $request->name,
            'condition' => $request->condition,
            'img' => $request->img,
            'user_id' => $user_id,
        ]);

        // Save the history instance
        $history->save();


        return response()->json(['message' => 'History added successfully'], 201);
    }
    public function listHistories()
    {
        // Get the authenticated user ID
        $user_id = auth()->user()->id;

        // Retrieve all histories for the user
        $histories = History::where('user_id', $user_id)->get();

        return response()->json([$histories], 200);
    }
}
