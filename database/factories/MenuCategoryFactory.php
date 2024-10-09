<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MenuCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }

    public function navigation()
    {
        return $this->create([
            'name' => 'Navegação',
            'order' => 1
        ]);
    }

    public function menus()
    {
        return $this->create([
            'name' => 'Menus',
            'order' => 2
        ]);
    }

    
    public function permissions()
    {
        return $this->create([
            'name' => 'Permissões',
            'order' => 3
        ]);
    }
    
    public function admin()
    {
        return $this->create([
            'name' => 'Administração',
            'order' => 4
        ]);
    }
}
