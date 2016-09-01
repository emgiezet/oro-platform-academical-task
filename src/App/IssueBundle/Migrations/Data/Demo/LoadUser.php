<?php
/**
 * Created by PhpStorm.
 * User: mgz
 * Date: 01.09.16
 * Time: 17:59.
 */
namespace App\IssueBundle\Migrations\Data\Demo;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\UserBundle\Entity\User;

class LoadUser extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 10;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; ++$i) {
            $user = new User();
            $user->setEmail('test'.$i.'@academical.oro');
            $user->setFirstName('Test'.$i);
            $user->setLastName('Oro');
            $user->setUsername('test'.$i);
            $manager->persist($user);
            $this->addReference('user-'.$i);
        }
        $manager->flush();
    }
}
