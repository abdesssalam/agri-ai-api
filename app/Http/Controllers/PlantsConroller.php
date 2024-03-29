<?php

namespace App\Http\Controllers;

use App\Models\note;
use App\Models\Plant;
use Illuminate\Http\Request;

class PlantsConroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $plants = $user->plants;
        return response()->json(['plants' => $plants], 200);
    }


    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required',
            'condition' => 'required',
            'img' => 'required',
        ]);

        $plant = new Plant([
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'condition' => $request->condition,
            'img' => $request->img
        ]);
        $plant->save();

        return response()->json(['plant' => $plant], 201);
    }



    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required',
            'condition' => 'required',
            'img' => 'required',
        ]);

        $plant = Plant::findOrFail($id);
        $plant->update($request->all());

        return response()->json(['plant' => $plant], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->delete();
        return response()->json(['message' => 'Plant deleted successfully'], 200);
    }

    public function toGarden(Request $request, string $id)
    {
        $plant = Plant::findOrFail($id);

        $plant->update(['is_garden' => 1]);

        return response()->json(['message' => 'Plant moved successfully'], 200);
    }
    public function fromGarden(Request $request, string $id)
    {
        $plant = Plant::findOrFail($id);

        $plant->update(['is_garden' => 0]);

        return response()->json(['message' => 'Plant removed successfully'], 200);
    }

    public function add_note(Request $request)
    {

        $request->validate([
            'plant_id' => 'required',
            'text' => 'required',
        ]);

        $note = new note(
            [
                'plant_id' => $request->plant_id,
                'text' => $request->text
            ]
        );
        // return response()->json([
        //     'message' => 'ok'
        // ]);
        $note->save();
        return response()->json([
            'message' => 'ok'
        ]);
    }

    public function remove_note(string $id)
    {
        $note = note::findOrFail($id);
        $note->delete();
        return response()->json([
            'message' => 'ok'
        ]);
    }

    public function edit_note(Request $request, string $id)
    {
        $note = note::findOrFail($id);
        $note->text = $request->text;
        $note->save();
        return response()->json([
            'message' => 'ok'
        ]);
    }
    public function get_notes(string $id)
    {
        $plant = Plant::findOrFail($id);

        return response()->json(
            $plant->notes

        );
    }
}
