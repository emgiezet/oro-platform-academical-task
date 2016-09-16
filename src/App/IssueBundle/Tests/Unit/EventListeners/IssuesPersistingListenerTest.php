<?php

namespace App\IssueBundle\Tests\Unit\EventListeners;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\EventListeners\IssuesPersistingListener;
use App\IssueBundle\Model\Service\Collaboration;
use App\IssueBundle\Model\Service\IssueCodeGenerator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IssuesPersistingListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandlesIssueWhenPersist()
    {
        /**
         * @var IssueUpdateStamp
         */
        $issueUpdateStamp = $this->getMockBuilder('App\IssueBundle\Model\Service\IssueDateUpdater')
            ->disableOriginalConstructor()
            ->getMock();

        $issueUpdateStamp->expects($this->atLeastOnce())
            ->method('updateStamps');

        /**
         * @var IssueCodeGenerator
         */
        $issueCodeGenerator = $this->getMockBuilder('App\IssueBundle\Model\Service\IssueCodeGenerator')
            ->disableOriginalConstructor()
            ->getMock();

        $issueCodeGenerator->expects($this->atLeastOnce())
            ->method('populateCode');

        /**
         * @var Collaboration
         */
        $collaboration = $this->getMockBuilder('App\IssueBundle\Model\Service\CollaboratorsCollector')
            ->disableOriginalConstructor()
            ->getMock();

        /*
         * @var TokenStorageInterface
         */
        $tokenStorage = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $listener = new IssuesPersistingListener(
            $issueUpdateStamp,
            $issueCodeGenerator,
            $collaboration,
            $tokenStorage
        );

        $lifeCycleArgs = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $issue = new Issue();

        $lifeCycleArgs->expects($this->atLeastOnce())
            ->method('getEntity')
            ->will($this->returnValue($issue));

        $entityManager = $this->getMockBuilder(
            'Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $lifeCycleArgs->expects($this->atLeastOnce())
            ->method('getEntityManager')
            ->will($this->returnValue($entityManager));

        $listener->prePersist($lifeCycleArgs);
    }

    public function testHandlesIssueWhenUpdate()
    {
        /**
         * @var IssueUpdateStamp
         */
        $issueUpdateStamp = $this->getMockBuilder('App\IssueBundle\Model\Service\IssueDateUpdater')
            ->disableOriginalConstructor()
            ->getMock();

        $issueUpdateStamp->expects($this->atLeastOnce())
            ->method('updateStamps');

        /**
         * @var IssueCodeGenerator
         */
        $issueCodeGenerator = $this->getMockBuilder('App\IssueBundle\Model\Service\IssueCodeGenerator')
            ->disableOriginalConstructor()
            ->getMock();

        $issueCodeGenerator->expects($this->atLeastOnce())
            ->method('populateCode');

        /**
         * @var CollaboratorsCollector
         */
        $collaboratorsCollector = $this->getMockBuilder('App\IssueBundle\Model\Service\CollaboratorsCollector')
            ->disableOriginalConstructor()
            ->getMock();

        /*
         * @var TokenStorageInterface
         */
        $tokenStorage = $this->getMockBuilder(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $listener = new IssuesPersistingListener(
            $issueUpdateStamp,
            $issueCodeGenerator,
            $collaboratorsCollector,
            $tokenStorage
        );

        $lifeCycleArgs = $this->getMockBuilder('Doctrine\ORM\Event\PreUpdateEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $issue = new Issue();

        $lifeCycleArgs->expects($this->atLeastOnce())
            ->method('getEntity')
            ->will($this->returnValue($issue));

        $entityManager = $this->getMockBuilder(
            'Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $lifeCycleArgs->expects($this->atLeastOnce())
            ->method('getEntityManager')
            ->will($this->returnValue($entityManager));

        $classMetaData = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetaData')
            ->disableOriginalConstructor()
            ->getMock();

        $entityManager->expects($this->atLeastOnce())
            ->method('getClassMetadata')
            ->will($this->returnValue($classMetaData));

        $uow = $this->getMockBuilder('Doctrine\ORM\UnitOfWork')
            ->disableOriginalConstructor()
            ->getMock();

        $entityManager->expects($this->atLeastOnce())
            ->method('getUnitOfWork')
            ->will($this->returnValue($uow));

        $listener->preUpdate($lifeCycleArgs);
    }
}
