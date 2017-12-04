<?php
declare(strict_types=1);

namespace AppBundle\DataFixtures;

use AppBundle\Entity\BlogUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DefaultContent extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new BlogUser('blogadmin', 'hunter2');
        $manager->persist($user);

        $manager->flush();
    }
}