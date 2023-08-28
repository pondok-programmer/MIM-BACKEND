<?php

namespace App\Http\Controllers\Konten;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ArtikelDakwah;
use App\Models\InfoKajian;
use App\Models\User;

class SearchController extends Controller
{
    public function searchKonten(Request $request)
{
    $query = $request->get('Search');

    // Pencarian di tabel InfoKajian
    $infoKajianResults = InfoKajian::where('judul', 'like', "%{$query}%")
                            ->orWhere('waktu', 'like', "%{$query}%")
                            ->orWhere('tanggal', 'like', "%{$query}%")
                            ->get();

    // Pencarian di tabel ArtikelDakwah
    $artikelDakwahResults = ArtikelDakwah::where('judul', 'like', "%{$query}%")
                              ->orWhere('author', 'like', "%{$query}%")
                              ->get();

    // Menggabungkan dua hasil pencarian
    $results = [
        'InfoKajian' => $infoKajianResults,
        'ArtikelDakwah' => $artikelDakwahResults
    ];

    return response()->json($results);
}

    public function searchUser(Request $request){
        $query = $request->get('Search');

        $results = User::where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('tempat_lahir', 'like', "%{$query}%")
                    ->orWhere('no_telp', 'like', "%{$query}%")
                    ->get();

        return response()->json($results);
    }

}

