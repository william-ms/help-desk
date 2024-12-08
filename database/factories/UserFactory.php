<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function admin($Companies, $Departaments, $Role)
    {
        $User = $this->create([
            'name' => "Administrador",
            'email' => "admin@helpdesk.com.br",
            'first_login' => 1,
            'email_verified_at' => now(),
        ]);

        $User->companies()->sync($Companies->pluck('id'));
        $User->departaments()->sync($Departaments->pluck('id'));
        $User->assignRole($Role->id);
        $log_admin_data['companies'] = ['values' => $Companies->pluck('name'),'title' => "Atribuiu o usuário as <b>empresas</b>"];
        $log_admin_data['departaments'] = ['values' => $Departaments->pluck('name'),'title' => "Atribuiu o usuário aos <b>departamentos</b>"];
        $log_admin_data['role'] = ['value' => "Atribuiu o usuário à função de <b>{$Role->name}</b>"];
        register_log($User, 'update', 200, $log_admin_data);

        return $User;
    }

    public function technical($Companies, $Departaments, $Role)
    {
        $User = $this->create([
            'name' => "Técnico",
            'email' => "technical@helpdesk.com.br",
            'email_verified_at' => now(),
        ]);

        $User->companies()->sync($Companies->pluck('id'));
        $User->departaments()->sync($Departaments->pluck('id'));
        $User->assignRole($Role->id);
        $log_admin_data['companies'] = ['values' => $Companies->pluck('name'),'title' => "Atribuiu o usuário as <b>empresas</b>"];
        $log_admin_data['departaments'] = ['values' => $Departaments->pluck('name'),'title' => "Atribuiu o usuário aos <b>departamentos</b>"];
        $log_admin_data['role'] = ['value' => "Atribuiu o usuário à função de <b>{$Role->name}</b>"];
        register_log($User, 'update', 200, $log_admin_data);

        return $User;
    }

    public function user($Companies, $Departaments, $Role)
    {
        $User = $this->create([
            'name' => "Usuário",
            'email' => "user@helpdesk.com.br",
            'email_verified_at' => now(),
        ]);
        
        $User->companies()->sync($Companies->pluck('id'));
        $User->departaments()->sync($Departaments->pluck('id'));
        $User->assignRole($Role->id);
        $log_admin_data['companies'] = ['values' => $Companies->pluck('name'),'title' => "Atribuiu o usuário as <b>empresas</b>"];
        $log_admin_data['departaments'] = ['values' => $Departaments->pluck('name'),'title' => "Atribuiu o usuário aos <b>departamentos</b>"];
        $log_admin_data['role'] = ['value' => "Atribuiu o usuário à função de <b>{$Role->name}</b>"];
        register_log($User, 'update', 200, $log_admin_data);

        return $User;
    }
}
