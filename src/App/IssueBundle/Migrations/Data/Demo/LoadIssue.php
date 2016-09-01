<?php

namespace App\IssueBundle\Migrations\Data\Demo;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\Entity\Priority;
use App\IssueBundle\Entity\Resolution;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIssue extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 20;
    }

    public function load(ObjectManager $manager)
    {
        $priorities = ['priority-blocker', 'priority-major', 'priority-minor'];
        $resolutions = ['resolution-new', 'resolution-progress', 'resolution-review', 'resolution-resolved', 'resolution-rejected', 'resolution-closed'];

        for ($i = 0; $i < 50; ++$i) {
            $issue = new Issue();
            $issue->setPriority($this->getReference($priorities[array_rand($priorities, 1)]));
            $issue->setResolution($this->getReference($resolutions[array_rand($resolutions, 1)]));
            $issue->setAsignee($this->getReference('user-'.mt_rand(0, 4)));
            $issue->setReporter($this->getReference('user-'.mt_rand(0, 4)));
            $issue->setCode('ISSUE-'.mt_rand(0, 123));
            $issue->setDescription('Lorem ipsum amet dolor sit');
            $manager->persist($issue);
        }
        $manager->flush();
    }
}
