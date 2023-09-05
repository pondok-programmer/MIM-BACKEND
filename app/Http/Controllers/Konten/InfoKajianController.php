<?php

namespace App\Http\Controllers\Konten;

use Carbon\Carbon;
use Hashids\Hashids;
use App\Models\InfoKajian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class InfoKajianController extends Controller
{
    public function createKajian(Request $request){
        $validator = Validator::make($request->all(),[
            'judul' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,gif,webp,jpg|max:2000',
            'waktu' => 'required|string',
            'tanggal' => 'required|date',
            'link' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                "message"   =>$validator->errors()
                ]);
        }
        $uploadedImage = Cloudinary::upload($request->file('gambar')->getRealPath(), [
            'folder' => 'MIM/thumnail_infokajian'
        ]);
        $kontens = InfoKajian::create([
            'judul' => $request->judul,
            'gambar' => $uploadedImage->getSecurePath(),
            'waktu'=> $request->waktu,
            'tanggal' => Carbon::createFromFormat('l, d-F-Y', $request->tanggal)->format('Y-m-d'),
            'link'=> $request->link
        ]);

        $hashids = new Hashids('your-secret-salt', 10);
        $hashedId = $hashids->encode($kontens->id);
        $konten = $kontens->toArray();
        $konten['id'] = $hashedId;
        return response()->json([
            'Massage' => 'ContentCreatedSuccessfully',
            'user' => $konten
        ]);
    }

    public function showKajian(){

        $konten = InfoKajian::orderBy('created_at', 'desc')->paginate(8);

        return response()->json([
            'data' => $konten
        ]);
    }

    public function showOneKajian($id){
        $hashids = new Hashids('your-secret-salt', 10);
        $konten = InfoKajian::where('id', $hashids->decode($id))->first();
        
        if(!$konten){
            return response()->json([
                'Kajian' => null, 'Not Found'
            ], 204);
        }
        return response()->json([
            'Artikel' => $konten
        ]);
    }

    public function updateKajian(Request $request, $id){
        $hashids = new Hashids('your-secret-salt', 10);
        $konten = InfoKajian::find($hashids->decode($id)[0]);
        if (!$konten) {
            return response()->json([
                'message' => 'Kajian tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(),[
            'judul' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,gif,webp,jpg|max:2000',
            'waktu' => 'required|string',
            'tanggal' => 'required|date',
            'link' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Fails',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('gambar')) {
            // Menghapus foto lama jika ada
            if ($konten->gambar) {
                $publicId = pathinfo($konten->gambar, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            $uploadedImage = Cloudinary::upload($request->file('gambar')->getRealPath(), [
                'folder' => 'MIM/thumnail_infokajian'
            ]);

            $updateKonten = [
                'judul' => $request->judul,
                'gambar' => $uploadedImage->getSecurePath(),
                'waktu' => $request->waktu,
                'tanggal' => $request->tanggal,
                'link'=> $request->link
            ];

            $konten->update($updateKonten);

            return response()->json([
                'message' => 'Kajian Berhasil Di Update',
                'Artikel' => $updateKonten
            ]);
        }
    }

    public function deleteKajian($id){
            $hashids = new Hashids('your-secret-salt', 10);
            InfoKajian::destroy($hashids->decode($id));

                return response()->json([
                    'Artikel' => 'Kajian Ini Berhasil DiHapus'
                ]);
    }
}
