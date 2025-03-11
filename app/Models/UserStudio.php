<?php

namespace App\Models;

use App\Models\User;
use App\Models\MasterData\Studio;
use Sis\TrackHistory\HasTrackHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserStudio extends Model
{
    use HasFactory, SoftDeletes, HasTrackHistory;

    protected $fillable = [
        'user_id',
        'studio_id',
    ];

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class, 'studio_id', 'id');
    }
}
