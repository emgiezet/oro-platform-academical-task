<?php
/**
 * Created by PhpStorm.
 * User: mgz
 * Date: 01.09.16
 * Time: 20:52.
 */
namespace App\IssueBundle\Tests\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

class IssueControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient(array(), $this->generateBasicAuthHeader());
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', $this->getUrl('app_issue_create'));
//        echo( $this->getUrl('app_issue_create'));

        $form = $crawler->selectButton('Save and Close')->form();
        $form['app_issuebundle_issue[summary]'] = 'New issue';
        $form['app_issuebundle_issue[description]'] = 'New description';
        $form['app_issuebundle_issue[code]'] = 'N123';
        $form['app_issuebundle_issue[asignee]'] = '1';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
//        $this->assertContains('Issue saved', $crawler->html());
    }

    /**
     * @depends testCreate
     */
    public function testUpdate()
    {
        $response = $this->client->requestGrid(
            'app-issue-grid',
            array('app-issue-grid[_filter][reporter][value]' => 'admin admin')
        );

        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        var_dump($result);

        $crawler = $this->client->request(
            'GET',
            $this->getUrl('app_issue_update', array('id' => $result['id']))
        );

        $form = $crawler->selectButton('Save and Close')->form();
        $form['app_issuebundle_issue[summary]'] = 'Issue summary updated';
        $form['app_issuebundle_issue[description]'] = 'Description updated';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Task saved', $crawler->html());
    }

    /**
     * @depends testUpdate
     */
    public function testView()
    {
        $response = $this->client->requestGrid(
            'app-issue-grid',
            array('tasks-grid[_filter][reporterName][value]' => 'John Doe')
        );

        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $this->client->request(
            'GET',
            $this->getUrl('app_issuebundle_issue_view', array('id' => $result['id']))
        );
        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Task updated - Tasks - Activities', $result->getContent());
    }

    /**
     * @depends testUpdate
     */
    public function testIndex()
    {
        $this->client->request('GET', $this->getUrl('app_issuebundle_issue_index'));
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Task updated', $result->getContent());
    }
}
