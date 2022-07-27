<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PsEndpoint>
 */
class PsEndpointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $exten = fake()->randomNumber(3, true);
        return [
            'id' => $exten,
            'transport' => 'transport-udp',
            'aors' => $exten,
            'auth' => $exten,
            'context' => 'testing',
            'disallow' => 'all',
            'allow' => 'ulaw',
            'direct_media' => 'no',
            'rtp_symmetric' => 'yes'
        ];
    }
}
