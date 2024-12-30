<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $guard = ['id'];
    protected $fillable = [
        'product_name',
        'slug',
        'image',
        'category_id',
        'description',
        'size',
        'length',
        'thickness',
        'project',
        'status',
        'qr_code_path',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed(); // Tetap mengambil kategori meskipun sudah dihapus (soft delete)
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function project()
    {
        return $this->hasMany(Project::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id(); // Set user_id saat membuat
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id(); // Set user_id saat mengupdate
        });

        static::deleting(function ($model) {
            $model->deleted_by = Auth::id(); // Set user_id saat menghapus
            $model->save(); // Pastikan kolom disimpan sebelum soft delete
        });
    }
}
