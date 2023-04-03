<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarCustomer extends Model
{
    use HasFactory;
    protected $table = 'daftar_customer';
    protected $guarded = [];
}
