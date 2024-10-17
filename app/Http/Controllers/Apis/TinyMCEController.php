<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\ValidationException;

class TinyMCEController extends Controller
{
    public function add_images(Request $request)
    {
        try {
			$validatedData = $request->validate([
				'file' => 'required|image',
			]);

            $NameFile = Str::of($request->file)->slug('-');
            $NameStored = 'storage/uploads/' . $NameFile . '.webp';
            $destination = public_path($NameStored);

            Image::make($request->file('file')->path())->encode("webp")->save($destination);

            // Retorne a URL pública do arquivo para o TinyMCE
            return response()->json(['location' => url('storage') . "/uploads/{$NameFile}.webp"]);
        } catch (ValidationException $e) {
			return response()->json(['error' => "Imagem inválida, por favor informe uma imagem em um formato válido!"]);
        }
    }
}