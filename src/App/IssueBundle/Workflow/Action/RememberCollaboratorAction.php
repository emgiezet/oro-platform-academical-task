<?php

namespace App\IssueBundle\Workflow\Action;

use App\IssueBundle\Model\Service\CollaboratorsCollector;
use Oro\Bundle\WorkflowBundle\Model\Action\ActionInterface;
use Oro\Component\ConfigExpression\ExpressionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RememberCollaboratorAction implements ActionInterface
{
    /**
     * @var CollaboratorsCollector
     */
    private $collaboration;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * RememberCollaboratorAction constructor.
     *
     * @param Collaboration         $collaboration
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        CollaboratorsCollector $collaboration,
        TokenStorageInterface $tokenStorage
    ) {
        $this->collaboration = $collaboration;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Execute action.
     *
     * @param mixed $context
     */
    public function execute($context)
    {
        if ($this->tokenStorage->getToken()) {
            $this->collaboration->updateCollaborators(
                $context->getEntity(),
                [$this->tokenStorage->getToken()->getUser()]
            );
        }
    }

    /**
     * @param array $options
     */
    public function initialize(array $options)
    {
    }

    /**
     * @param ExpressionInterface $condition
     */
    public function setCondition(ExpressionInterface $condition)
    {
    }
}
