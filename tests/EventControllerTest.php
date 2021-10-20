<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EventControllerTest extends ApiTestCase
{
    private $token;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetAllEvents(): void
    {
        $params = ['headers' => ['authorization' => 'Bearer '.$this->getToken()]];
        static::createClient()->request('GET', '/api/events/all',$params);

        $this->assertResponseStatusCodeSame(200);
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
