<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PsEndpoint>
 */
class PsEndpointFactory extends Factory
{
    private static $exten = 1000;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        self::$exten++;
        return [
            'id' => self::$exten,
            'transport' => 'transport-udp',
            'aors' => self::$exten,
            'auth' => self::$exten,
            'context' => 'testing',
            'disallow' => 'all',
            'allow' => 'ulaw',
            'direct_media' => 'no',
            'rtp_symmetric' => 'yes'
        ];
    }
}
