<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogItem extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_id',
        'user_id',
        'type',
        'label',
        'status',
        'action',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function action_as_string() {
        $action = '';

        foreach(json_decode($this->action) as $k => $v) {
            if(is_array($v)) {
                foreach($v as $m => $n) {
                    $action .= $n;
                }
            } else {
                $action .= $v;
            }
        }

        return $action;
    }
}
