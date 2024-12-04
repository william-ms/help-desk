<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public string $type = "ticket";

    public $fields = [
        'uuid' => 'uuid',
        'company_id' => 'empresa',
        'departament_id' => 'departamento',
        'category_id' => 'categoria',
        'subcategory_id' => 'subcategoria',
        'requester_id' => 'solicitante',
        'assignee_id' => 'solicitado',
        'subject' => 'assunto',
        'status' => 'status',
        'action' => 'ação',
    ];

    protected $fillable = [
        'uuid',
        'company_id',
        'departament_id',
        'category_id',
        'subcategory_id',
        'requester_id',
        'assignee_id',
        'transfer_assignee_id',
        'subject',
        'status',
        'action'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function responses() {
        return $this->hasMany(TicketResponse::class);
    }

    public function last_user_response() {
        return $this->hasOne(TicketResponse::class)->where('type', 1)->whereNotNull('user_id')->latest();
    }

    public function company() 
    {
        return $this->belongsTo(Company::class);
    }

    public function departament() 
    {
        return $this->belongsTo(Departament::class);
    }

    public function category() 
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory() 
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id', 'id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id', 'id');
    }

    public function log()
    {
        return $this->hasOne(Log::class, 'model_id', 'id')->where('model_type', $this->type);
    }

    public function log_values() 
    {
        return [];
    }
}
