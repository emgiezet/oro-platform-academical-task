<?php
/**
 * Created by PhpStorm.
 * User: mgz
 * Date: 29.08.16
 * Time: 12:42.
 */
namespace App\IssueBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class IssueBundle implements Migration, OrderedMigrationInterface
{
    public function getOrder()
    {
        return 1000;
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $resolutionTable = $schema->createTable('app_issue_resolution');
        $resolutionTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $resolutionTable->addColumn('label', 'string', ['lenght' => 255]);
        $resolutionTable->addColumn('priority', 'integer');
        $resolutionTable->addIndex(['label'], 'app_issue_resolution_label_idx', []);
        $resolutionTable->addIndex(['priority'], 'app_issue_priority_label_idx', []);
        $resolutionTable->setPrimaryKey(['id']);

        $priorityTable = $schema->createTable('app_issue_priority');
        $priorityTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $priorityTable->addColumn('label', 'string', ['lenght' => 255]);
        $priorityTable->addColumn('priority', 'integer');
        $priorityTable->addIndex(['label'], 'app_issue_priority_label_idx', []);
        $priorityTable->setPrimaryKey(['id']);

        $issueTable = $schema->createTable('app_issue');
        $issueTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $issueTable->addColumn('code', 'string', ['lenght' => 255, 'null' => true, 'default' => null]);
        $issueTable->addColumn('summary', 'string', ['lenght' => 255, 'null' => true, 'default' => null]);
        $issueTable->addColumn('description', 'text', ['null' => true, 'default' => null]);
        $issueTable->addColumn('type', 'smallint', ['null' => true, 'default' => null]);
        $issueTable->addColumn('created', 'datetime', ['null' => true, 'default' => null]);
        $issueTable->addColumn('updated', 'datetime', ['null' => true, 'default' => null]);
        $issueTable->addColumn('issue_organization_id', 'integer', ['null' => false]);
        $issueTable->addColumn('issue_priority_id', 'integer', ['null' => true, 'default' => null]);
        $issueTable->addColumn('issue_resolution_id', 'integer', ['null' => false, 'default' => null]);
        $issueTable->addColumn('issue_assigne_id', 'integer', ['null' => true]);
        $issueTable->addColumn('issue_updated_by_id', 'integer', ['null' => true]);
        $issueTable->addColumn('parent_id', 'integer', ['null' => true, 'default' => null]);
        $issueTable->addColumn('issue_reporter_id', 'integer', ['null' => true, 'default' => null]);
        $issueTable->addColumn('deleted', 'smallint', ['null' => true, 'default' => 0]);
        $issueTable->addForeignKeyConstraint('app_issue', ['parent_id'], ['id']);
        $issueTable->addForeignKeyConstraint('oro_user', ['issue_assigne_id'], ['id']);
        $issueTable->addForeignKeyConstraint('oro_user', ['issue_reporter_id'], ['id']);
        $issueTable->addForeignKeyConstraint('app_issue_resolution', ['issue_resolution_id'], ['id']);
        $issueTable->addForeignKeyConstraint('app_issue_priority', ['issue_priority_id'], ['id']);

        $issueTable->setPrimaryKey(['id']);
        $issueTable->addIndex(['code'], 'app_issue_code_idx', []);
        $issueTable->addIndex(['issue_updated_by_id'], 'issue_updated_by_id_idx', []);
        $issueTable->addIndex(['issue_organization_id'], 'issue_organization_id_idx', []);
        $issueTable->addIndex(['issue_priority_id'], 'issue_priority_id_idx', []);
        $issueTable->addIndex(['issue_resolution_id'], 'issue_resolution_id_idx', []);
        $issueTable->addIndex(['issue_reporter_id'], 'issue_reporter_id_idx', []);
        $issueTable->addIndex(['issue_assigne_id'], 'issue_assigne_id_idx', []);
        $issueTable->addIndex(['parent_id'], 'parent_id_idx', []);

        $relatedTable = $schema->createTable('app_issue_related');
        $relatedTable->addColumn('issue_id', 'integer');
        $relatedTable->addColumn('related_id', 'integer');
        $relatedTable->addForeignKeyConstraint('app_issue', ['issue_id'], ['id']);
        $relatedTable->addForeignKeyConstraint('app_issue', ['related_id'], ['id']);

        $collaboratorsTable = $schema->createTable('app_issue_collaborators');
        $collaboratorsTable->addColumn('issue_id', 'integer');
        $collaboratorsTable->addColumn('user_id', 'integer');
        $collaboratorsTable->addForeignKeyConstraint('app_issue', ['issue_id'], ['id']);
        $collaboratorsTable->addForeignKeyConstraint('oro_user', ['user_id'], ['id']);
    }
}
