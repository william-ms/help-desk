<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketResponse extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ticket_response';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'type',
        'response',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
