<?php

// app/Models/Barcode.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndiaPostBarcode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_type','state', 'code', 'prefix', 'range_from', 'range_to', 'postfix', 'availables'
    ];

    // Generate the next available barcode
    public function getNextBarcode()
    {
        $randomNumber = rand(1, 9);
        $nextNumber = $this->range_from;
        $nextNumber = ($this->range_to - $this->availables);
        return $this->prefix . $nextNumber . $randomNumber .$this->postfix;
    }


    // public function getNextBarcode()
    // {
    //     $nextNumber = ($this->range_to - $this->availables);
    //     // $nextNumber = $this->range_from + ($this->range_to - $this->availables);
    //     return $this->prefix . $nextNumber . $this->postfix;
    // }

}

