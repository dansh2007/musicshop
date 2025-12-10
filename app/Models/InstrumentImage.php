<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumentImage extends Model
{
    use HasFactory;

    protected $fillable = ['instrument_id', 'image_path'];

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
}
