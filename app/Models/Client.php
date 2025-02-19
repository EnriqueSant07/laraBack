<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'address', 'company_name'];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
