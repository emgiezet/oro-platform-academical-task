<?php

namespace App\IssueBundle\Tests\Unit\DependencyInjection;

use App\IssueBundle\DependencyInjection\IssueExtension;
use Oro\Bundle\TestFrameworkBundle\Test\DependencyInjection\ExtensionTestCase;

class IssueExtensionTest extends ExtensionTestCase
{
    /**
     * Test Extension.
     */
    public function testExtension()
    {
        $extension = new IssueExtension();

        $this->loadExtension($extension);

        $this->assertDefinitionsLoaded([
            'issue_bundle.importexport.template_fixture.issue',
        ]);

        $this->assertEquals('issue', $extension->getAlias());
    }
}
