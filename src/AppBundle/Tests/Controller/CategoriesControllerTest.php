<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoriesControllerTest extends WebTestCase
{
    public function testCategories()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/categories');
    }

}
