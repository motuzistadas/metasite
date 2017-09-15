<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrdersControllerTest extends WebTestCase
{
    public function testOrder()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/order');
    }

}
