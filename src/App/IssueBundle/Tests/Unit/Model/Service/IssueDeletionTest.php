<?php

namespace App\IssueBundle\Tests\Unit\Model\Service;

use App\IssueBundle\Model\Service\IssueSoftDeleter;
use Doctrine\ORM\EntityManager;
use App\IssueBundle\Entity\Issue;

class IssueDeletionTest extends \PHPUnit_Framework_TestCase
{
    public function testDeletesIssue()
    {
        $entityManager = $this->loadEntityManager();

        $entityManager->expects($this->atLeastOnce())
            ->method('persist');

        $issueDeletion = new IssueSoftDeleter($entityManager);

        $issueDeletion->deleteIssueById(2);

        $issue = new Issue();

        $issueDeletion->deleteIssue($issue);

        $this->assertTrue($issue->isDeleted());
    }

    /**
     * @return EntityManager
     */
    private function loadEntityManager()
    {
        $entityManagerChainMethods = ['getRepository', 'persist', 'flush'];

        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->setMethods(array_merge($entityManagerChainMethods, ['findOneBy']))
            ->disableOriginalConstructor()
            ->getMock();

        foreach ($entityManagerChainMethods as $method) {
            $entityManager->expects($this->any())
                ->method($method)
                ->will($this->returnValue($entityManager));
        }

        $entityManager->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue(new Issue()));

        return $entityManager;
    }
}
