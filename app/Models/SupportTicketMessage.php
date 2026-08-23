<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'support_ticket_id',
        'messageable_id',
        'messageable_type',
        'body'
    ];
    public function byAdmin()
    {
        return $this->messageable_type == Admin::class;
    }

    public function byFranchise()
    {
        return $this->messageable_type == Franchise::class;
    }
    public function bycms()
    {
        return $this->messageable_type == CMS::class;
    }
    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'messageable_id', 'id');
    }
    public function cms()
    {
        return $this->belongsTo(CMS::class, 'messageable_id', 'id');
    }
    /**
     * @return BelongsTo
     */
    public function supportTicket()
    {
        return $this->belongsTo(SupportTicket::class);
    }
}
