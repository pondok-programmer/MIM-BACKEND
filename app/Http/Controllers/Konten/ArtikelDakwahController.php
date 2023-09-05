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
        $artikels = ArtikelDakwah::orderBy('created_at', 'desc')->paginate(8);
        $hashids = new Hashids('your-secret-salt', 10);

        $transformedData = $artikels->getCollection()->map(function($article) use ($hashids) {
            $array = $article->toArray();
            $encodedId = $hashids->encode($article->id);
            if ($encodedId) {
                $array['id'] = $encodedId;
            } else {
                return response()->json([
                    'Data User' => 'Id Tidak Bisa DIHash'
                ]);
            }
            return $array;
        })->toArray();

        $artikels->setCollection(collect($transformedData));

        return response()->json([
            'Artikel' => $artikels
        ]);

    }

    public function showOneArtikel($id){
        $hashids = new Hashids('your-secret-salt', 10);
        $artikels = ArtikelDakwah::where('id', $hashids->decode($id))->first();
        
        if(!$artikels){
            return response()->json([
                'Artikel' => null, 'Not Found'
            ], 204);
        }
        $artikel = $artikels->toArray();
        $encodedId = $hashids->encode($artikels->id);
        if ($encodedId) {
            $artikel['id'] = $encodedId ;
        } else {
            return response()->json([
                'Data User' => 'Id Tidak Bisa DIHash'
            ]);    
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
