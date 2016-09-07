<?php
/**
 * Created by PhpStorm.
 * User: mgz
 * Date: 06.09.16
 * Time: 11:23
 */

namespace App\IssueBundle\Listener;

use App\IssueBundle\Entity\Issue;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class ColaboratorsListener
 * @package App\IssueBundle\Listener
 */
class ColaboratorsListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function onUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        if ($entity instanceof Issue) {
            /**
             * @var $entity Issue
             */
            $asignee = $entity->getAsignee();
            $reporter = $entity->getReporter();
            $entity->addCollaborator($asignee);
            $entity->addCollaborator($reporter);
            $entityManager->flush();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->onUpdate($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->onUpdate($args);
    }

}