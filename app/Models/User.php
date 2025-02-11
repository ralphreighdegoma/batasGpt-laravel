<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomVerificationEmail;


class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasApiTokens;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'title',
        'bio',
        'email',
        'password',
        'avatar_url',
        'role_id',
        'address',
        'aboutMe'
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
     * Determine if the user can access the specified panel.
     *
     * @param Panel $panel The panel to check access for.
     * @return bool True if the user can access the panel, false otherwise.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    //avatars url add storage
    public function getAvatarUrlAttribute()
    {
        return asset('storage/' . $this->avatar);
    }
    

    public function sendVerificationEmail()
    {
        //custom verification email
        $this->notify(new CustomVerificationEmail($this));
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

    /**
     * Get the role that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the contact associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contact()
    {
        return $this->hasOne(Contact::class);
    }

    public function avatarName(): Attribute
    {
        return new Attribute(
            get: fn () => $this->name,
        );
    }

    public function avatarUrl(): Attribute
    {
        return new Attribute(
            get: fn () => empty($this->avatar_url) ? 'https://ui-avatars.com/api/?name=' . urlencode($this->name) : $this->avatar_url,
        );
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'connections', 'following_id')->whereNotNull('email_verified_at');
    }

}
