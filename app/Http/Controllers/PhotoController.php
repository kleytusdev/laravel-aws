<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
  public function store(Request $request)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $imageName = $request->file('photo')->store('photos', 'public');

    Photo::create(['photo_1' => $imageName]);

    return response()->json(['message' => 'Imagen subida correctamente'], 200);
}
}
