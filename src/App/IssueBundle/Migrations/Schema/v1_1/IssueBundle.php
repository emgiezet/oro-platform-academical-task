<?php
/**
 * Created by PhpStorm.
 * User: mgz
 * Date: 01.09.16
 * Time: 17:35.
 */
namespace App\IssueBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtension;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;

class IssueBundle implements Migration, NoteExtensionAwareInterface, OrderedMigrationInterface, ActivityExtensionAwareInterface
{
    public function getOrder()
    {
        return 1001;
    }
    /** @var NoteExtension */
    protected $noteExtension;

    /**
     * @var ActivityExtension
     */
    protected $activityExtension;

    public function up(Schema $schema, QueryBag $queries)
    {
//        $this->activityExtension->addActivityAssociation($schema, 'oro_email', 'app_issue');
        $this->noteExtension->addNoteAssociation($schema, 'app_issue');
    }

    /**
     * {@inheritdoc}
     */
    public function setNoteExtension(NoteExtension $noteExtension)
    {
        $this->noteExtension = $noteExtension;
    }
    /**
     * @param ActivityExtension $activityExtension
     */
    public function setActivityExtension(ActivityExtension $activityExtension)
    {
        $this->activityExtension = $activityExtension;
    }
}
