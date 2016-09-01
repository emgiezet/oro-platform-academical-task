<?php

namespace App\IssueBundle\Migrations\Data\ORM;

use App\IssueBundle\Entity\Resolution;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadResolution extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $resolutionClosed = new Resolution();
        $resolutionClosed->setLabel('Closed');
        $resolutionClosed->setPriority(100);
        $manager->persist($resolutionClosed);

        $resolutionRejected = new Resolution();
        $resolutionRejected->setLabel('Rejected');
        $resolutionRejected->setPriority(100);
        $manager->persist($resolutionRejected);

        $resolutionResolved = new Resolution();
        $resolutionResolved->setLabel('Resolved');
        $resolutionResolved->setPriority(90);
        $manager->persist($resolutionResolved);

        $resolutionReview = new Resolution();
        $resolutionReview->setLabel('In Review');
        $resolutionReview->setPriority(60);
        $manager->persist($resolutionReview);

        $resolutionProgress = new Resolution();
        $resolutionProgress->setLabel('In Progress');
        $resolutionProgress->setPriority(50);
        $manager->persist($resolutionProgress);

        $resolutionNew = new Resolution();
        $resolutionNew->setLabel('New');
        $resolutionNew->setPriority(10);
        $manager->persist($resolutionNew);

        $manager->flush();

        $this->addReference('resolution-new', $resolutionNew);
        $this->addReference('resolution-progress', $resolutionProgress);
        $this->addReference('resolution-review', $resolutionReview);
        $this->addReference('resolution-resolved', $resolutionResolved);
        $this->addReference('resolution-rejected', $resolutionRejected);
        $this->addReference('resolution-closed', $resolutionClosed);
    }
}
