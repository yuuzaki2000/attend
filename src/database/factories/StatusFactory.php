<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
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
            'user_id' => function(){
                return User::factory()->create()->id;
            },
            'date' => \Carbon\Carbon::now()->format('Y-m-d'),
            'content' => '勤務中',
        ];
    }
}
