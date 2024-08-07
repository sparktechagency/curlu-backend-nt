<?php

namespace App\Http\Controllers;

use App\Http\Requests\AboutRequest;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{

    public function index()
    {
        $about_us = AboutUs::first();
        return response()->json($about_us);
    }


    public function store(AboutRequest $request)
    {
        $about_us = new AboutUs();
        $about_us->title = $request->title;
        $about_us->description = $request->description;
        $about_us->save();
        return response()->json(['message' => 'About us saved successfully']);
    }

    public function show(string $id)
    {
        $about_us = AboutUs::find($id);

        if (!$about_us) {
            return response()->json(['message' => 'About us not found'], 404);
        }

        return response()->json($about_us);
    }


    public function update(Request $request, string $id)
    {
        $about_us = AboutUs::first();

        if (!$about_us) {
            return response()->json(['message' => 'About us not found'], 404);
        }

        $about_us->title = $request->title;
        $about_us->description = $request->description;
        $about_us->save();
        return response()->json(['message' => 'About us updated successfully']);
    }

    public function destroy(string $id)
    {
        $about_us = AboutUs::find($id);

        if (!$about_us) {
            return response()->json(['message' => 'About us not found'], 404);
        }

        $about_us->delete();

        return response()->json(['message' => 'About us deleted successfully']);
    }
}
