<?php

namespace App\IssueBundle\Tests\Unit\Entity;

class PriorityTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestHelperTrait;

    public function testSettingAndGettingValues()
    {
        $this->consistencyCheck('App\IssueBundle\Entity\Priority');
    }
}
