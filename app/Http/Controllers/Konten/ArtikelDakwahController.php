<?php

namespace App\Http\Controllers\Konten;

use Illuminate\Http\Request;
use App\Models\ArtikelDakwah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ArtikelDakwahController extends Controller
{
    public function createArtikel(Request $request){
        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string'],
            'gambar'=>['required', 'mimes:jpeg,jpg,png,svg,webp','max:2048', 'image'],
            'deskripsi' => ['required', 'string'],
            'author' => ['required', 'string'],
        ]);

        if($validator->fails()){
            return response()->json([
                'Error' => true,
                'Massage' => $validator->errors()
            ]);
        }

        $uploadedImage = Cloudinary::upload($request->file('gambar')->getRealPath(), [
            'folder' => 'MIM/ArtikelDakwah'
        ]);

        $artikel = ArtikelDakwah::create([
            'judul' => $request->judul,
            'gambar' => $uploadedImage->getSecurePath(),
            'deskripsi' => $request->deskripsi,
            'author' => $request->author
        ]);

        return response()->json([
            'Massage' => 'artikelCreatedSuccessfully',
            'Artikel' => $artikel
        ]);
    }

    public function showArtikel(){

        $artikel = ArtikelDakwah::all();

        return response()->json([
            'Artikel' => $artikel
        ]);
    }

    public function updateArtikel(Request $request, $id){
        $artikel = ArtikelDakwah::find($id);

        $validator = Validator::make($request->all(), [
            'judul' => ['required', 'string'],
            'gambar'=>['required', 'mimes:jpeg,jpg,png,svg,webp','max:2048', 'image'],
            'deskripsi' => ['required', 'string'],
            'author' => ['required', 'string'],
        ]);

        if($validator->fails()){
            return response()->json([
                'Error' => true,
                'Massage' => $validator->errors()
            ]);
        }

        if ($request->hasFile('gambar')) {
            // Menghapus foto lama jika ada
            if ($artikel->gambar) {
                $publicId = pathinfo($artikel->gambar, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }
            // Upload gambar baru ke Cloudinary
            $uploadedImage = Cloudinary::upload($request->file('gambar')->getRealPath(), [
                'folder' => 'MIM/ArtikelDakwah'
            ]);
            // Simpan URL gambar ke dalam database
            $artikel->gambar = $uploadedImage->getSecurePath();
        }

        $updateArtikel = [
            'judul' => $request->judul,
            'gambar' => $artikel->gambar,
            'deskripsi' => $request->deskripsi,
            'author' => $request->author
        ];

        $artikel->update($updateArtikel);

        return response()->json([
            'Massage' => 'artikelUpdatedSuccessfully',
            'Artikel' => $updateArtikel
        ]);
    }

    public function deleteArtikel($id){

        ArtikelDakwah::destroy($id);

        return response()->json([
            'Massage' => 'artikelDeletedSuccessfully',
        ]);
    }
}
