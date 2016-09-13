<?php

namespace App\IssueBundle\Model\Action;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\Model\Service\CollaboratorsCollector;
use App\IssueBundle\Model\Service\IssueDateUpdater;
use Oro\Bundle\WorkflowBundle\Model\Action\AbstractAction;
use Oro\Bundle\WorkflowBundle\Model\ContextAccessor;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class SaveNoteAuthorAsCollaborator
 * @package App\IssueBundle\Model\Action
 */
class SaveNoteAuthorAsCollaborator extends AbstractAction
{
    /**
     * @var issueDateUpdater
     */
    private $issueDateUpdater;

    /**
     * @var collaboratorsUpdater
     */
    private $collaboratorsUpdater;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param ContextAccessor $contextAccessor
     */
    public function __construct(
        ContextAccessor $contextAccessor,
        IssueDateUpdater $issueDateUpdater,
        CollaboratorsCollector $collaboratorsUpdater,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct($contextAccessor);

        $this->issueDateUpdater = $issueDateUpdater;
        $this->collaboratorsUpdater = $collaboratorsUpdater;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param mixed $context
     */
    protected function executeAction($context)
    {
        $values = $context->getValues();
        $note = $values['data'];
        $target = $note->getTarget();

        if ($target instanceof Issue) {
            $this->updateUpdateStamp($target);
            $this->updateCollaborators($target);
        }
    }

    /**
     * @param array $options
     */
    public function initialize(array $options)
    {
        //
    }

    /**
     * @param Issue $issue
     */
    private function updateUpdateStamp(Issue $issue)
    {
        $this->issueDateUpdater->populateCreationAndUpdateStamps($issue);
    }

    /**
     * @param Issue $issue
     */
    private function updateCollaborators(Issue $issue)
    {
        if ($this->tokenStorage->getToken() && $this->tokenStorage->getToken()->getUser()) {
            $this->collaboratorsUpdater->updateCollaborators($issue, [$this->tokenStorage->getToken()->getUser()]);
        }
    }
}
