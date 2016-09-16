<?php

namespace App\IssueBundle\Tests\Unit\Entity;

use App\IssueBundle\Entity\Resolution;

class ResolutionTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestHelperTrait;

    public function testSettingAndGettingValues()
    {
        $this->consistencyCheck('App\IssueBundle\Entity\Resolution');
    }

    public function testConvertsToString()
    {
        $resolution = new Resolution();

        $resolution->setLabel('test');
        $resolution->setPriority(123);

        $this->assertTrue((bool) strstr((string) $resolution, 'test'));
        $this->assertTrue($resolution->getPriority() === 123);
    }
}
