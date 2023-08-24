<?php

namespace App\Models;

use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'email', 'status', 'message', 'comment'];

    protected static function newFactory(): ApplicationFactory
    {
        return ApplicationFactory::new();
    }

}
