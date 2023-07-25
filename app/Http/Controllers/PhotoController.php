<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PhotoController extends Controller
{
  public function index()
  {
    $photos = Photo::get()->map->getAttributes();

    return Inertia::render('Photo', compact('photos'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $file = $request->file('photo');
    $imageName = $file->store('photos', 'public');

    // Guardar el enlace de la imagen en el modelo Photo
    Photo::create(['photo_1' => $imageName]);

    // Guardar la imagen en Amazon S3
    Storage::disk('s3')->put($imageName, file_get_contents($file));

    return response()->json(['message' => 'Imagen subida correctamente'], 200);
  }

  public function destroy($id)
  {
    // Eliminar la foto de la base de datos
    $photo = Photo::findOrFail($id);
    $photo->delete();

    // Eliminar la foto del almacenamiento local (carpeta storage/app/public/photos)
    Storage::disk('public')->delete($photo->photo_1);

    // Eliminar la foto del almacenamiento en Amazon S3
    Storage::disk('s3')->delete($photo->photo_1);

    return response()->json(['message' => 'Foto eliminada correctamente'], 200);
  }
}
