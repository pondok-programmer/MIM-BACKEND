<?php

namespace App\Http\Controllers\Konten;

use Carbon\Carbon;
use App\Models\AmalYaumi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AmalYaumiController extends Controller
{
    public function createAmal(Request $request){
        $validate = Validator::make($request->all(),[
            'subuh' => 'required|boolean',
            'zuhur' => 'required|boolean',
            'ashar' => 'required|boolean',
            'maghrib' => 'required|boolean',
            'isya' => 'required|boolean',
            'tahajud' => 'required|boolean',
            'dhuha' => 'required|boolean',
            'witir' => 'required|boolean',
            'puasa' => 'required|boolean',
            'dzikir' => 'required|boolean',
            'kajian_subuh' => 'required|boolean',
        ]);

        if($validate->fails()){
            return response()->json([
                'Error' => true,
                'Message' => $validate->errors()
            ]);
        }
        $users = auth()->user();
        $user = $users->id;
        $amal = AmalYaumi::create([
            'user_id' =>$user,
            'hari' => Carbon::now()->toDateString(),
            'subuh' =>$request->subuh,
            'zuhur' =>$request->zuhur,
            'ashar'=>$request->ashar,
            'maghrib'=>$request->maghrib,
            'isya'=>$request->isya,
            'tahajud'=>$request->tahajud,
            'dhuha'=>$request->dhuha,
            'witir'=>$request->witir,
            'puasa'=>$request->puasa,
            'dzikir'=>$request->dzikir,
            'kajian_subuh'=>$request->kajian_subuh,
        ]);

        return response()->json([
            'Massage' => 'amalYaumiSuccessfullyCreate', 
            'AmalYaumi' => $amal
        ]);
    }

    public function showAmal(){
        $amal = AmalYaumi::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'AmalYaumi' => $amal
        ]);
    }
}
