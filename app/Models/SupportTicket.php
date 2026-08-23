<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'auth_id', 'status'];
    public function message()
    {
        return $this->hasMany(SupportTicketMessage::class);
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function isOpen(): bool
    {
        return $this->status == 1;
    }

    public function isClose(): bool
    {
        return $this->status == 2;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOpen(Builder $query)
    {
        return $query->where("{$this->getTable()}.status", 1);
    }

    /**
     * @param Builder $query
     * @param string $date
     * @return Builder
     */
    public function scopeFromDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '>=', $date);
    }

    /**
     * @param Builder $query
     * @param string $date
     * @return Builder
     */
    public function scopeToDate(Builder $query, string $date): Builder
    {
        return $query->whereDate("{$this->getTable()}.created_at", '<=', $date);
    }
}
