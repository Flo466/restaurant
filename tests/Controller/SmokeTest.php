<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    public function testApiDocUrlIsSuccessfull() : void {
        $client = self::createClient();
        $client->request(method: 'GET', uri: 'api/doc');

        self::assertResponseIsSuccessful();
        
    }

    public function testApiAccountUrlIsSecure() : void {
        $client = self::createClient();
        $client->request(method: 'GET', uri: 'api/me');

        self::assertResponseStatusCodeSame('401');
        
    }
}
