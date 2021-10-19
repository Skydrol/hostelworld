<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\UserRepository;

class ProfileControllerTest extends ApiTestCase
{

    public function testRegister(): void
    {
        $client = static::createClient();
        $randomNumber = rand(0,100000000);
        $profileTesterEmail = 'profile-tester'.$randomNumber.'@hostelworld.com';
        $params = [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => $profileTesterEmail,
                'password' => 'veryhardpassword-'.$profileTesterEmail
            ],
        ];
        $client->request('GET', '/api/register',$params);
        $this->assertResponseStatusCodeSame(201);


    }

    public function testLogin(): void
    {
        $client = static::createClient();
        $params = [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'profile-tester@hostelworld.com',
                'password' => 'veryhardpassword'
            ],
        ];
        $client->request('GET', '/api/register',$params);
        $this->assertResponseStatusCodeSame(201);

    }
}
