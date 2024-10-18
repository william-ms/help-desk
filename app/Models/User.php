<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    public string $type = "usuário";

    public $fields = [
        'name' => 'nome',
        'email' => 'email',
        'password' => 'senha',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'name',
        'email',
        'first_login',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'user_company');
    }

    public function departaments()
    {
        return $this->belongsToMany(Departament::class, 'user_departament');
    }

    public function log()
    {
        return $this->hasOne(Log::class, 'model_id', 'id')->where('model_type', $this->type);
    }

    public function log_values() {
        $changes = $this->getChanges();
        $data = [
            'remember_token' => '',
            'first_login' => '',
            'email_verified_at' => '',
        ];

        if(!empty($changes) && !array_key_exists('deleted_at', $changes)) {
            if(!empty($changes['password'])) {
                $data['password'] = ['value' => 'Alterou a <b>senha</b>'];
            }

            if(!empty($changes['status'])) {
                $data['status'] = ($changes['status'] == 1)
                ? ['value' => '<b>Reativou</b> o usuário']
                : ['value' => '<b>Desativou</b> o usuário'];
            }
        } else {
            $data['status'] = (empty($this->status) || $this->status == 1)
            ? ['value' => 'Status <b>Ativo</b>']
            : ['value' => 'Status <b>Inativo</b>'];

            $data['password'] = ['value' => 'Senha <b>********</b>'];
        }

        return $data;
    }
}
