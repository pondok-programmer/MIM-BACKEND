<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BacaanSholat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BacaanSholatController extends Controller
{
    public function createBacaan(Request $request){
        $validator = Validator::make($request->all(), [
            'arab' => 'required',
            'latin' => 'required',
            'terjemahan' => 'required',
            'voice_arab' => 'required',
            'voice_terjemahan' => 'required',
        ]);

        if($validator->fails()){
            response()->json([
                'Error' => true,
                'Massage' => $validator->errors()
            ]);
        }

        $bacaan = BacaanSholat::create([
            'arab' => $request->arab,
            'latin' => $request->latin,
            'terjemahan' => $request->terjemahan,
            'voice_arab' => $request->voice_arab,
            'voice_terjemahan' => $request->voice_terjemahan,
        ]);

        response()->json([
            'Error' => true,
            'Massage' => $bacaan
        ]);
        
    }

    public function showBacaan(){
        $bacaan = BacaanSholat::all();

        return response()->json([
            'Massage' => $bacaan
        ]);
    }
}
