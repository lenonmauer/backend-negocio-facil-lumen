<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\FileRepository;
use App\Helpers\ImageResize;
use Intervention\Image\ImageManager;

class UploadController extends Controller
{
  public function postPhoto(Request $request, FileRepository $fileRepo)
  {
    $user = $request->auth;
    $destinationPath = env('PHOTOS_UPLOAD_PATH');

    if (!$request->hasFile('foto')) {
      return response()->json(['error' => 'Nenhum arquivo enviado.'], 400);
    }

    $file = $request->file('foto');

    if (strripos($file->getMimetype(), 'image') === false) {
      return response()->json(['error' => 'O arquivo deve ser uma imagem.'], 400);
    }

    $filename = $this->generateRandomUniqueFilename($destinationPath) . '.' . $file->extension();
    $filepath = $destinationPath . '/' . $filename;
    $mime = $file->getMimetype();

    if (!$file->move($destinationPath, $filename)) {
      return response()->json(['error' => 'Ocorreu um erro durante o upload da foto.'], 400);
    }

    $manager = new ImageManager(['driver' => 'gd']);
    $manager->make($filepath)->fit(255)->save();

    $fileData = [
      'upload_user_id' => $user->id,
      'name' => $filename,
      'path' => $destinationPath,
      'mime_type' => $mime,
      'size' => filesize($filepath),
    ];

    $fileRecord = $fileRepo->create($fileData);

    $data = [
      'id' => $fileRecord->id,
      'path' => $fileRecord->getPublicPath(),
    ];

    return response()->json($data, 200);
  }

  private function generateRandomUniqueFilename($path)
  {
    do {
      $filename = md5(uniqid() .  '/' . microtime(true));
      $fullpath = $path . '/' . $filename;
    } while(file_exists($fullpath));

    return $filename;
  }
}