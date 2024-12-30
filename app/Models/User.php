<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
  use HasFactory, Notifiable, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'first_name',
    'last_name',
    'slug',
    'email',
    'email_verified_at',
    'password',
    'role',
    'phone_number',
    'address',
    'province',
    'country',
    'zip_code',
    'avatar',
    'project',
    'is_active',
  ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($user) {
      $user->slug = self::createUniqueSlug($user->first_name . ' ' . $user->last_name, self::class);
    });

    static::updating(function ($user) {
      if ($user->isDirty('first_name') || $user->isDirty('last_name')) {
        $user->slug = self::createUniqueSlug($user->first_name . ' ' . $user->last_name, self::class);
      }
    });
  }

  /**
   * Create a unique slug.
   */
  private static function createUniqueSlug($name, $model)
  {
    $slug = Str::slug($name);
    $originalSlug = $slug;
    $count = 1;

    while ($model::where('slug', $slug)->exists()) {
      $slug = "{$originalSlug}-{$count}";
      $count++;
    }

    return $slug;
  }

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
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function defaultProfilePhotoUrl()
  {
    return asset('assets/img/avatars/1.png'); // Default avatar path
  }

  public function getProfilePhotoUrlAttribute()
  {
    return $this->avatar
      ? asset($this->avatar)
      : $this->defaultProfilePhotoUrl();
  }

  public function getJoinedAtAttribute()
  {
    return Carbon::parse($this->created_at)->format('d F Y'); // Format tanggal bergaya
  }

  // App\Models\User.php

  public function getFullNameAttribute()
  {
    return "{$this->first_name} {$this->last_name}";
  }

  public function activities()
  {
    return $this->hasMany(UserActivity::class);
  }
  public function getNameAttribute()
  {
    return "{$this->first_name} {$this->last_name}";
  }
  //products
  public function product()
  {
    return $this->hasMany(Product::class);
  }
  //activity
  public function activity()
  {
    return $this->hasMany(UserActivity::class);
  }

  // Opsional: Tambahkan accessor untuk normalisasi role
  public function getRoleAttribute($value)
  {
    return strtolower($value);
  }
}
