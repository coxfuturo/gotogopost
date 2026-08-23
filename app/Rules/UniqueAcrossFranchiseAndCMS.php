<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueAcrossFranchiseAndCMS implements Rule
{
    protected $column;

    public function __construct($column)
    {
        $this->column = $column;
    }

    public function passes($attribute, $value)
    {
        $inFranchise = DB::table('franchises')->where($this->column, $value)->exists();
        $inCMS = DB::table('c_m_s')->where($this->column, $value)->exists();

        return !$inFranchise && !$inCMS;
    }

    public function message()
    {
        return 'The :attribute has already been taken.';
    }
}
