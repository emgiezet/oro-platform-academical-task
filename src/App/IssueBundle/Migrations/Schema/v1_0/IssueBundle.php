<?php
/**
 * Created by PhpStorm.
 * User: mgz
 * Date: 29.08.16
 * Time: 12:42
 */

namespace App\IssueBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;

class IssueBundle implements Migration, NoteExtensionAwareInterface
{

    /** @var NoteExtension */
    protected $noteExtension;

    /**
     * {@inheritdoc}
     */
    public function setNoteExtension(NoteExtension $noteExtension)
    {
        $this->noteExtension = $noteExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {

        $resolutionTable = $schema->createTable('app_issue_resolution');
        $resolutionTable->addColumn('id', 'integer', ['autoincrement' => true] );
        $resolutionTable->addColumn('label', 'string', ['lenght'=>255] );
        $resolutionTable->addColumn('priority', 'integer');
        $resolutionTable->setPrimaryKey(['id']);


        $priorityTable = $schema->createTable('app_issue_priority');
        $priorityTable->addColumn('id', 'integer', ['autoincrement' => true] );
        $priorityTable->addColumn('label', 'string', ['lenght'=>255] );
        $priorityTable->addColumn('priority', 'integer');
        $priorityTable->setPrimaryKey(['id']);


        $issueTable = $schema->createTable('app_issue');
        $issueTable->addColumn('id', 'integer', ['autoincrement' => true] );
        $issueTable->addColumn('code', 'string', ['lenght'=>255] );
        $issueTable->addColumn('summary', 'string', ['lenght'=>255] );
        $issueTable->addColumn('description', 'text');
        $issueTable->addColumn('type', 'smallint');
        $issueTable->addColumn('status', 'integer');
        $issueTable->addColumn('notes', 'integer');
        $issueTable->addColumn('created', 'datetime');
        $issueTable->addColumn('updated', 'datetime');
        $issueTable->addColumn('priority', 'integer');
        $issueTable->addColumn('resolution', 'integer');
        $issueTable->addColumn('reporter', 'integer');
        $issueTable->addColumn('asignee', 'integer');
        $issueTable->addForeignKeyConstraint('oro_user', ['asignee'], ['id']);
        $issueTable->addForeignKeyConstraint('oro_user', ['reporter'], ['id']);
        $issueTable->addForeignKeyConstraint('oro_user', ['asignee'], ['id']);
        $issueTable->addForeignKeyConstraint('app_issue_resolution', ['resolution'], ['id']);
        $issueTable->addForeignKeyConstraint('app_issue_priority', ['priority'], ['id']);
        $issueTable->addColumn('parent', 'integer');
        $issueTable->setPrimaryKey(['id']);


        $this->noteExtension->addNoteAssociation($schema, 'app_issue');

        //todo: create m2n table for releated issues



        //todo: create m2n table for involved users

    }



}