<?php

namespace App\IssueBundle\Migrations\Data\ORM;

use App\IssueBundle\Entity\Priority;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPriority extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $blockerPriority = new Priority();
        $blockerPriority->setLabel('blocker');
        $blockerPriority->setPriority(100);
        $manager->persist($blockerPriority);

        $majorPriority = new Priority();
        $majorPriority->setLabel('major');
        $majorPriority->setPriority(70);
        $manager->persist($majorPriority);

        $minorPriority = new Priority();
        $minorPriority->setLabel('minor');
        $minorPriority->setPriority(30);
        $manager->persist($minorPriority);
        $manager->flush();

        $this->addReference('priority-blocker', $blockerPriority);
        $this->addReference('priority-major', $majorPriority);
        $this->addReference('priority-minor', $minorPriority);
    }
}
