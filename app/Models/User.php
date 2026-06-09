<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;
    protected $fillable = [
        'nom',
        'prenoms',
        'email',
        'password',
        'role',
        'statut',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin():bool{
        return $this->role==='admin';
    }

    public function isValidated():bool{
        return $this->statut==='valide';
    }

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

    // Un professeur peut avoir plusieurs réservations
    public function reservations(){
    return $this->hasMany(Reservation::class);
    }


    public function isDesactive(): bool
{
    return $this->statut === 'desactive';
}

}
