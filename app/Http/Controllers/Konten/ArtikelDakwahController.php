<?php

namespace App\Http\Controllers\Konten;

use Hashids\Hashids;
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
            ], 402);
        }

        $uploadedImage = Cloudinary::upload($request->file('gambar')->getRealPath(), [
            'folder' => 'MIM/ArtikelDakwah'
        ]);

        $artikels = ArtikelDakwah::create([
            'judul' => $request->judul,
            'gambar' => $uploadedImage->getSecurePath(),
            'deskripsi' => $request->deskripsi,
            'author' => $request->author
        ]);

        $hashids = new Hashids('your-secret-salt', 10);
        $hashedId = $hashids->encode($artikels->id);
        $artikel = $artikels->toArray();
        $artikel['id'] = $hashedId;
        return response()->json([
            'Massage' => 'artikelCreatedSuccessfully',
            'Artikel' => $artikel
        ]);
    }

    public function showArtikel(){

        $artikels = ArtikelDakwah::orderBy('created_at', 'desc')->paginate(3);

        return response()->json([
            'Artikel' => $artikels
        ]);
    }

    public function showOneArtikel($id){
        $hashids = new Hashids('your-secret-salt', 10);
        $artikel = ArtikelDakwah::where('id', $hashids->decode($id))->first();
        
        if(!$artikel){
            return response()->json([
                'Artikel' => null, 'Not Found'
            ], 204);
        }
        return response()->json([
            'Artikel' => $artikel
        ]);
    }

    public function updateArtikel(Request $request, $id){
        $hashids = new Hashids('your-secret-salt', 10);
        $artikel = ArtikelDakwah::find($hashids->decode($id)[0]);

        if (!$artikel) {
            return response()->json([
                'Error' => true,
                'Message' => 'Artikel tidak ditemukan'
            ], 404);
        }
        
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
        $hashids = new Hashids('your-secret-salt', 10);
        ArtikelDakwah::destroy($hashids->decode($id));

        return response()->json([
            'Massage' => 'artikelDeletedSuccessfully',
        ]);
    }
}
