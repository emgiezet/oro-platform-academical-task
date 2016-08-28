<?php
namespace App\IssueBundle\Migrations\Data\ORM;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\Entity\Priority;
use App\IssueBundle\Entity\Resolution;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPriority implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $priority = new Priority();
        $priority->setLabel('blocker');
        $priority->setPriority(100);
        $manager->persist($priority);

        $priority = new Priority();
        $priority->setLabel('major');
        $priority->setPriority(70);
        $manager->persist($priority);

        $minorPriority = new Priority();
        $minorPriority->setLabel('minor');
        $minorPriority->setPriority(30);
        $manager->persist($minorPriority);
        $manager->flush();
    }
}
