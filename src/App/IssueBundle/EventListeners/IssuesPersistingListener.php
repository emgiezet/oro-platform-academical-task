<?php

namespace App\IssueBundle\EventListeners;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\Model\Service\CollaboratorsCollector;
use App\IssueBundle\Model\Service\IssueCodeGenerator;
use App\IssueBundle\Model\Service\IssueDateUpdater;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IssuesPersistingListener
{
    /**
     * @var issueDateUpdater
     */
    private $issueDateUpdater;

    /**
     * @var IssueCodeGenerator
     */
    private $issueCodeGenerator;

    /**
     * @var CollaboratorsCollector
     */
    private $collaboratorsCollector;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * IssuesPersistingListener constructor.
     *
     * @param IssueDateUpdater       $issueDateUpdater
     * @param IssueCodeGenerator     $issueCodeGenerator
     * @param CollaboratorsCollector $collaboration
     * @param TokenStorageInterface  $tokenStorage
     */
    public function __construct(
        IssueDateUpdater $issueDateUpdater,
        IssueCodeGenerator $issueCodeGenerator,
        CollaboratorsCollector $collaboratorsCollector,
        TokenStorageInterface $tokenStorage
    ) {
        $this->issueDateUpdater = $issueDateUpdater;
        $this->issueCodeGenerator = $issueCodeGenerator;
        $this->collaboratorsCollector = $collaboratorsCollector;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof Issue) {
            $this->handleIssue($args->getEntityManager(), $args->getEntity());
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        if ($args->getEntity() instanceof Issue) {
            $entity = $args->getEntity();

            $this->handleIssue($args->getEntityManager(), $entity);

            $em = $args->getEntityManager();
            $uow = $em->getUnitOfWork();
            $meta = $em->getClassMetadata(get_class($entity));
            $uow->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }

    /**
     * @param EntityManager $entityManager
     * @param Issue         $issue
     */
    private function handleIssue(EntityManager $entityManager, Issue $issue)
    {
        $this->issueDateUpdater->updateStamps($issue);

        if (!$issue->getCode()) {
            $this->issueCodeGenerator->populateCode($entityManager, $issue);
        }
    }
}
