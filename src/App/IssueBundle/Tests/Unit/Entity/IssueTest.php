<?php

namespace App\IssueBundleTests\Unit\Entity;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\Tests\Unit\Entity\EntityTestHelperTrait;

class IssueTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestHelperTrait;

    public function testSettingAndGettingValues()
    {
        $this->consistencyCheck('App\IssueBundle\Entity\Issue');

        $issue = new Issue();
        $issue->setDeleted(true);

        $this->assertTrue($issue->isDeleted());
    }

    public function testAddingChildPopulatesParentField()
    {
        $issue = new Issue();
        $childIssue = new Issue();

        $issue->addChild($childIssue);

        $this->assertEquals($childIssue->getParent(), $issue);

        $issue = new Issue();
        $childIssue = new Issue();

        $issue->setChildren([$childIssue]);

        $this->assertEquals($childIssue->getParent(), $issue);
    }

    public function testIssueConvertsToString()
    {
        $issue = new Issue();

        $issue->setSummary('test');

        $this->assertTrue((bool) strstr((string) $issue, 'test'));
    }

    public function testStoryCanHaveSubtasks()
    {
        $issue = new Issue();
        $issue->setType(Issue::TYPE_BUG);

        $this->assertFalse($issue->isStory());

        $issue->setType(Issue::TYPE_STORY);

        $this->assertTrue($issue->isStory());
    }
}
