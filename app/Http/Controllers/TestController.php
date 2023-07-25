<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
  public function index(Request $request)
  {
    dd($request->all());
  }

  public function testPost(Request $request)
  {
    dd('Hola probando rama');
    $data = $request->all();
    // Obtener detalles de la imagen
    $foto_1 = $request->foto_1;
    // Decodificar la cadena de base64 en datos binarios
    $imageData = base64_decode($foto_1);

    // Obtener la extensión de la imagen
    $finfo = finfo_open();
    $mime_type = finfo_buffer($finfo, $imageData, FILEINFO_MIME_TYPE);
    finfo_close($finfo);

    $valid_extensions = [
      'image/jpeg' => 'jpeg',
      'image/png' => 'png',
      'image/gif' => 'gif',
    ];

    // Verificar si el tipo de contenido es válido
    if (isset($valid_extensions[$mime_type])) {
      $extension = $valid_extensions[$mime_type];
    } else {
      return response()->json(['error' => 'Tipo de imagen no válido'], 400);
    }

    // Generar un nombre único para el archivo de imagen
    $fileName = uniqid() . '.' . $extension;
    // Crear una instancia de Storage
    $storage = Storage::disk('public'); // Puedes usar otro disco, como 's3' o 'local'
    // Guardar la imagen en el almacenamiento
    $storage->put($fileName, $imageData);

    $photo = new Photo();
    $photo->photo_1 = $fileName;
    $photo->save();

    return response()->json(['message' => $data]);
  }
}
