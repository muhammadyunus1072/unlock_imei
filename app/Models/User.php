<?php

namespace App\Models;

use App\Models\MasterData\Studio;
use Laravel\Sanctum\HasApiTokens;
use Sis\TrackHistory\HasTrackHistory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, HasTrackHistory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
    | RELATIONSHIP
    */
    public function studios()
    {
        return $this->belongsToMany(Studio::class, 'user_studios', 'user_id', 'studio_id')->whereNull('user_studios.deleted_at');
    }

    public function userStudios()
    {
        return $this->hasMany(UserStudio::class, 'user_id', 'id');
    }
}
