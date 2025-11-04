<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{    
    //protected $table = 'users';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'admin',
        'admin_id',
        'email',
        'password',
        'rol_id',
        'plan_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function equipos() : BelongsToMany {
        return $this->belongsToMany(Equipo::class, 'equipo_veterinario', 'veterinario_id', 'equipo_id');
    }
    
    // public function roles(){
    //     return $this->belongsTo(Rol::class, 'role_user', 'role_id', 'user_id');
    // }

    public function rol() : BelongsTo {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
    
    public function admin(){
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function cajas(){
        return $this->hasMany(Caja::class);
    }

    public function usuarios(){
        return $this->hasMany(User::class, 'admin_id');
    }
}
