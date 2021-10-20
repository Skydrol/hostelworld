<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\UserRepository;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ProfileControllerTest extends ApiTestCase
{
    private $token;

    public function setUp(): void
    {
        self::bootKernel();
    }

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
        $client->request('POST', '/api/register',$params);
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
        $client->request('POST', '/api/login',$params);
        $this->assertResponseIsSuccessful();

    }


    /**
     * Use other credentials if needed.
     * @param array $body
     * @return string
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function getToken(): string
    {
        if ($this->token) {
            return $this->token;
        }

        $params = [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'profile-tester@hostelworld.com',
                'password' => 'veryhardpassword'
            ],
        ];

        $response = static::createClient()->request('POST', '/api/login',$params);

        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent());
        $this->token = $data->token;

        return $data->token;
    }
}
