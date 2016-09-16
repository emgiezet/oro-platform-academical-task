<?php

namespace App\IssueBundle\Model\Service;

use Doctrine\Common\Collections\ArrayCollection;
use App\IssueBundle\Entity\Issue;

/**
 * Class CollaboratorsCollector.
 */
class CollaboratorsCollector
{
    /**
     * @param Issue $issue
     * @param array $newUsers
     */
    public function updateCollaborators(Issue $issue, $newUsers = [])
    {
        $collaborators = $issue->getCollaborators();

        if ($collaborators === null) {
            $collaborators = new ArrayCollection();
        }

        foreach ($newUsers as $user) {
            if (!$collaborators->contains($user)) {
                $collaborators->add($user);
            }
        }

        if (!$collaborators->contains($issue->getAsignee()) && $issue->getAsignee()) {
            $collaborators->add($issue->getAsignee());
        }

        if (!$collaborators->contains($issue->getReporter()) && $issue->getReporter()) {
            $collaborators->add($issue->getReporter());
        }

        $issue->setCollaborators($collaborators);
    }
}
