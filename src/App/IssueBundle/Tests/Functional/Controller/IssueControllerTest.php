<?php
/**
 * Created by PhpStorm.
 * User: mgz
 * Date: 01.09.16
 * Time: 20:52.
 */
namespace App\IssueBundle\Tests\Controller;

use App\IssueBundle\Entity\Issue;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

class IssueControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], array_merge($this->generateBasicAuthHeader(), ['HTTP_X-CSRF-Header' => 1]));
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', $this->getUrl('app_issue_create'));

        $form = $crawler->selectButton('Save and Close')->form();
        $form['app_issuebundle_issue[summary]'] = 'New issue';
        $form['app_issuebundle_issue[description]'] = 'New description';
        $form['app_issuebundle_issue[code]'] = 'N123';
        $form['app_issuebundle_issue[asignee]'] = '1';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issue saved', $crawler->html());
    }

    public function testView()
    {
        $issue = $this->getIssue();

        if (!$issue) {
            throw new \RuntimeException(
                'No issue found in database that can be used for testing.'
            );
        }

        $this->client->request(
            'GET',
            $this->getUrl('app_issue_view', array('id' => $issue->getId()))
        );

        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $this->assertContains($issue->getCode(), $result->getContent());
        $this->assertContains(Issue::$typeArray[$issue->getType()], $result->getContent());
        $this->assertContains(
            $issue->getPriority()->getName(),
            $result->getContent()
        );
    }

    public function getIssue()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('IssueBundle:Issue')->findOneBy([]);
    }
}
