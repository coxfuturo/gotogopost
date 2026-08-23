<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageFromWebsites extends Model
{
    use HasFactory;
    protected $table = 'message_from_websites';

    protected $fillable = ['name', 'email', 'phone', 'option', 'message'];
}
