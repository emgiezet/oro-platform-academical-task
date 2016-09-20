<?php

namespace App\IssueBundle\ImportExport\TemplateFixture;

use App\IssueBundle\Entity\Issue;
use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;

/**
 * Class IssueFixture.
 */
class IssueFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'App\IssueBundle\Entity\Issue';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('TEST-123');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Issue();
    }

    /**
     * @param string $key
     * @param Issue  $entity
     */
    public function fillEntityData($key, $entity)
    {
        $userRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\UserBundle\Entity\User');

        $organizationRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\OrganizationBundle\Entity\Organization');

        switch ($key) {
            case 'TEST-123':
                $entity->setCode('TEST-123');
                $entity->setSummary('Fill Summary');
                $entity->setDescription('Fill Description');
                $entity->setCreated(new \DateTime());
                $entity->setAsignee($userRepo->getEntity('John Doo'));
                $entity->setReporter($userRepo->getEntity('John Doo'));
                $entity->setType(Issue::TYPE_BUG);
                $entity->setOrganization($organizationRepo->getEntity('default'));

                return;
        }

        parent::fillEntityData($key, $entity);
    }
}
