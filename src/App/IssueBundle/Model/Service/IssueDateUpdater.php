<?php

namespace App\IssueBundle\Model\Service;

use App\IssueBundle\Entity\Issue;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IssueDateUpdater
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    
    public function populateCreationAndUpdateStamps(Issue $issue)
    {
        if ($issue->getId() < 1) {
            $issue->setCreated(new \DateTime('now'));
        }

        $issue->setUpdated(new \DateTime('now'));
        $issue->setUpdatedBy($this->getCurrentUser());
    }

    /**
     * @return User|null
     */
    private function getCurrentUser()
    {
        if ($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();

            if ($user instanceof User) {
                return $user;
            }
        }
    }
}
