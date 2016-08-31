<?php
namespace App\IssueBundle\Controller;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\Form\IssueType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route("/", name="app_issue_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/create", name="app_issue_create")
     * @Template("AppBundle:Task:update.html.twig")
     */
    public function createAction(Request $request)
    {
        return $this->update(new Issue(), $request);
    }

    /**
     * @Route("/edit/{id}", name="app_issue_update", requirements={"id"="\d+"})
     * @Template("AppBundle:Task:update.html.twig")
     */
    public function editAction(Issue $issue, Request $request)
    {
        return $this->update($issue, $request);
    }

    private function update(Issue $task, Request $request)
    {
        $form = $this->createForm(new IssueType(), $task);

        return array(
            'entity' => $task,
            'form' => $form->createView(),
        );
    }
}
