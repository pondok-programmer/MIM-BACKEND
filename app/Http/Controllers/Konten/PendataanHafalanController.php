<?php

namespace App\Http\Controllers\Konten;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PendataanHafalan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PendataanHafalanController extends Controller
{
    public function createHafalan(Request $request){
        $validator = Validator::make($request->all(), [
            'juz' => 'required|string',
            'surah' => 'required|string',
            'ayat_awal' => 'required|string',
            'ayat_akhir' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'Error'    => true,
                'message'   =>$validator->errors()
                ]);
        }

        $hapalan = PendataanHafalan::create([
            'user_id' => auth()->user()->id,
            'juz' => $request->juz,
            'surah'=>$request->surah ,
            'ayat_awal'=> $request->ayat_awal,
            'ayat_akhir'=>$request->ayat_awal,
            'tanggal'=>Carbon::now()->toDateString(),
        ]);

        return response()->json([
            'Message'=>'Berhasil menambahkan hafalan',
            'Hafalan'=> $hapalan
        ]);
    }

    public function showHafalan(){

        $hapalan = PendataanHafalan::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'Message'=> $hapalan,
        ]);
    }

    public function updateHafalan(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'juz' => 'required|string',
            'surah' => 'required|string',
            'ayat_awal' => 'required|string',
            'ayat_akhir' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'Error'    => true,
                'message'   =>$validator->errors()
                ]);
        }

        $konten = PendataanHafalan::find($id);

        $updateHapalan = [
            'juz' => $request->juz,
            'surah'=>$request->surah ,
            'ayat_awal'=> $request->ayat_awal,
            'ayat_akhir'=>$request->ayat_akhir,
            'tanggal'=>Carbon::now()->toDateString(),
        ];

        $konten->update($updateHapalan);

        return response()->json([
            'message' => 'Hafalan Berhasil Di Update',
            'Data Hafalan' => $updateHapalan
        ]);
    }

    public function deleteHafalan($id){
        PendataanHafalan::destroy($id);

            return response()->json([
                'Artikel' => 'Hafalan Ini Berhasil DiHapus'
            ]);
    }
}
