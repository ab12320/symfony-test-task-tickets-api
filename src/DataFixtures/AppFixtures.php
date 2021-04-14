<?php

namespace App\DataFixtures;

use App\Factory\CustomerFactory;
use App\Factory\FlightFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        CustomerFactory::createMany(2);
        FlightFactory::createMany(2);
        $manager->flush();
    }
}
