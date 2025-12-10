<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Instrument;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with('instrument.brand', 'instrument.category')
            ->where('user_id', auth()->id())
            ->get()
            ->pluck('instrument');

        return view('favorites.index', compact('favorites'));
    }

    public function store(Instrument $instrument)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'instrument_id' => $instrument->id,
        ]);

        return back()->with('status', 'Додано до обраного.');
    }

    public function destroy(Instrument $instrument)
    {
        Favorite::where('user_id', auth()->id())
            ->where('instrument_id', $instrument->id)
            ->delete();

        return back()->with('status', 'Видалено з обраного.');
    }
}
