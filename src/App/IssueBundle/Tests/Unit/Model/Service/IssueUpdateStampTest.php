<?php

namespace App\IssueBundle\Tests\Unit\Model\Service;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\Model\Service\IssueDateUpdater;
use Oro\Bundle\UserBundle\Entity\User;

class IssueUpdateStampTest extends \PHPUnit_Framework_TestCase
{
    public function testUpdatesDates()
    {
        $user = new User();
        $user->setUsername('test user');

        $token = $this->getMockBuilder('\Symfony\Component\Security\Core\Authentication\Token')->setMethods(['getUser'])->getMock();
        $token->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($user));

        $tokenManager = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $tokenManager->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token));

        $issueUpdateStamp = new IssueDateUpdater($tokenManager);

        $issue = new Issue();

        $previousUpdatedAtDate = new \DateTime('now');
        $previousUpdatedAtDate->modify('-5 days');

        $issue->setUpdated($previousUpdatedAtDate);

        $issueUpdateStamp->updateStamps($issue);

        $this->assertInstanceOf('\DateTime', $issue->getCreated());
        $this->assertInstanceOf('\DateTime', $issue->getUpdated());

        $this->assertNotEquals($previousUpdatedAtDate, $issue->getUpdated());

        $this->assertEquals($user, $issue->getUpdatedBy());
    }
}
