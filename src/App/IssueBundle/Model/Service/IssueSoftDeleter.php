<?php

namespace App\IssueBundle\Model\Service;

use App\IssueBundle\Entity\Issue;
use Doctrine\ORM\EntityManager;

class IssueSoftDeleter
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * IssueDeletion constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function deleteIssueById($id)
    {
        /**
         * @var Issue $issue
         */
        $issue = $this->entityManager->getRepository('IssueBundle:Issue')->findOneBy([
            'id' => $id,
            'deleted' => false
        ]);

        if ($issue === null) {
            throw new \OutOfBoundsException('Issue not found.');
        }

        $this->deleteIssue($issue);

        return $issue->isDeleted();
    }

    /**
     * @param Issue $issue
     *
     * @return bool
     */
    public function deleteIssue(Issue $issue)
    {
        $issue->setDeleted(true);

        $this->entityManager->persist($issue);
        $this->entityManager->flush();

        return $issue->isDeleted();
    }
}
