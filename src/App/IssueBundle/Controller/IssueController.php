<?php

namespace App\IssueBundle\Controller;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\Entity\Resolution;
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
     * @Template("IssueBundle:Issue:update.html.twig")
     */
    public function createAction(Request $request)
    {
        $issue = new Issue();
        $resolution = $this->getDoctrine()->getRepository('IssueBundle:Resolution')->findOneByLabel('New');
        $issue->setReporter($this->getUser());
        $issue->setResolution($resolution);

        return $this->update($issue, $request);
    }

    private function update(Issue $issue, Request $request)
    {
        $form = $this->createForm(new IssueType(), $issue);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->get('oro_ui.router')->redirectAfterSave(
                array(
                    'route' => 'app_issue_update',
                    'parameters' => array('id' => $issue->getId()),
                ),
                array('route' => 'app_issue_index'),
                $issue
            );
        }

        return array(
            'entity' => $issue,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/edit/{id}", name="app_issue_update", requirements={"id"="\d+"})
     * @Template("IssueBundle:Issue:update.html.twig")
     */
    public function editAction(Issue $issue, Request $request)
    {
        return $this->update($issue, $request);
    }
}
