<?php

namespace App\IssueBundle\Twig;

class IssueTypeExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsFilters()
    {
        $issueTypesDefinition = $this->getMockBuilder('App\IssueBundle\Model\Service\IssueTypesDefinition')
            ->disableOriginalConstructor()
            ->getMock();

        $issueTypeExtension = new IssueTypeExtension($issueTypesDefinition);

        $filters = $issueTypeExtension->getFilters();

        $this->assertCount(1, $filters);
        $this->assertInstanceOf('\Twig_SimpleFilter', $filters[0]);
    }

    public function testReturnsName()
    {
        $issueTypesDefinition = $this->getMockBuilder('App\IssueBundle\Model\Service\IssueTypesDefinition')
            ->disableOriginalConstructor()
            ->getMock();

        $issueTypeExtension = new IssueTypeExtension($issueTypesDefinition);

        $this->assertEquals('issue_type_extension', $issueTypeExtension->getName());
    }
}
