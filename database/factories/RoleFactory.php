<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'guard_name' => 'web'
        ];
    }

    public function admin() {
        return $this->create(['name' => 'Administrador']);
    }

    public function user() {
        return $this->create(['name' => 'Usuário']);
    }

    public function technical($Permissions)
    {
        $Role = $this->create([
            'name' => "Técnico",
        ]);

        $Role->syncPermissions([
            $Permissions['company'][0],
            $Permissions['company'][1],
            $Permissions['company'][2],
            $Permissions['company'][3],
            $Permissions['company'][4],
            $Permissions['departament'][0],
            $Permissions['departament'][1],
            $Permissions['departament'][2],
            $Permissions['departament'][3],
            $Permissions['departament'][4],
        ]);

        return $Role;
    }
}
