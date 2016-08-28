<?php
namespace App\IssueBundle\Migrations\Data\ORM;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\Entity\Priority;
use App\IssueBundle\Entity\Resolution;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIssue implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
//        $priority      = new Priority();
//        $resolution    = new Resolution();
//        $majorPriority = new Issue();
//
//        $majorPriority->setLabel('major');
//        $manager->persist($majorPriority);
//
//        $importantTask = new Issue();
//        $importantTask->setSubject('Important task');
//        $importantTask->setDescription('This is an important task');
//        $importantTask->setDueDate(new \DateTime('+1 week'));
//        $importantTask->setPriority($majorPriority);
//        $manager->persist($importantTask);
//
//        $minorPriority = new Priority();
//        $minorPriority->setLabel('minor');
//        $manager->persist($minorPriority);
//
//        $unimportantTask = new Task();
//        $unimportantTask->setSubject('Unimportant task');
//        $unimportantTask->setDescription('This is a not so important task');
//        $unimportantTask->setDueDate(new \DateTime('+2 weeks'));
//        $unimportantTask->setPriority($minorPriority);
//        $manager->persist($unimportantTask);
//
//        $manager->flush();
    }
}
