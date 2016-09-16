<?php

namespace App\IssueBundle\Workflow\Action;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\Entity\Resolution;
use Doctrine\ORM\EntityManager;
use Oro\Bundle\WorkflowBundle\Model\Action\ActionInterface;
use Oro\Component\ConfigExpression\ExpressionInterface;

class SetResolutionAction implements ActionInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * SetResolutionAction constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $context
     */
    public function execute($context)
    {
        $resolution = $context->getData()->get('resolution');

        if ($resolution instanceof Resolution && $context->getEntity() instanceof Issue) {
            /*
             * @var Issue
             */
            $issue = $context->getEntity();

            $issue->setResolution($resolution);

            $this->entityManager->persist($issue);
            $this->entityManager->flush();
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
