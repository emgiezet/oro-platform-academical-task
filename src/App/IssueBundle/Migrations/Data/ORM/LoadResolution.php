<?php
namespace App\IssueBundle\Migrations\Data\ORM;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\Entity\Priority;
use App\IssueBundle\Entity\Resolution;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadResolution implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $resolution    = new Resolution();
        $resolution->setLabel('Closed');
        $resolution->setPriority(100);
        $manager->persist($resolution);

        $resolution    = new Resolution();
        $resolution->setLabel('Rejected');
        $resolution->setPriority(100);
        $manager->persist($resolution);

        $resolution    = new Resolution();
        $resolution->setLabel('Resolved');
        $resolution->setPriority(90);
        $manager->persist($resolution);

        $resolution    = new Resolution();
        $resolution->setLabel('In Review');
        $resolution->setPriority(60);
        $manager->persist($resolution);


        $resolution    = new Resolution();
        $resolution->setLabel('In Progress');
        $resolution->setPriority(50);
        $manager->persist($resolution);

        $resolution    = new Resolution();
        $resolution->setLabel('New');
        $resolution->setPriority(10);
        $manager->persist($resolution);

        $manager->flush();
    }
}
