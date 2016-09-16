<?php

namespace App\IssueBundle\Controller;

use App\IssueBundle\Entity\Issue;
use App\IssueBundle\Entity\Resolution;
use App\IssueBundle\Form\Type\IssueType;
use Oro\Bundle\UserBundle\Entity\User;
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

    /**
     * @param Issue      $issue
     * @param Request    $request
     * @param Issue|null $parent
     *
     * @return array
     */
    private function update(Issue $issue, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new IssueType(), $issue);
        $form->handleRequest($request);
        $issue = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($issue);
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
        if ($issue->isDeleted()) {
            throw new NotFoundHttpException();
        }

        return $this->update($issue, $request);
    }

    /**
     * @Route("/view/{id}", name="app_issue_view", requirements={"id"="\d+"})
     * @Template("IssueBundle:Issue:view.html.twig")
     */
    public function viewAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IssueBundle:Issue')->find($id);
        if ($entity->isDeleted()) {
            throw new NotFoundHttpException();
        }

        $editRoute = $this->generateUrl('app_issue_update', ['id' => $id]);

        return ['entity' => $entity, 'editRoute' => $editRoute];
    }

    /**
     * @Route("/create/{id}/subtask", name="app_issue_create_subtask", requirements={"id"="\d+"})
     * @Template("IssueBundle:Issue:update.html.twig")
     */
    public function createSubtaskAction(Issue $issue, Request $request)
    {
        if ($issue->getType() === Issue::TYPE_STORY) {
            $subtask = new Issue();
            $subtask->setType(Issue::TYPE_SUBTASK);
            $subtask->setParent($issue);
            return $this->update($subtask, $request);
        } else {
            return $this->redirectToRoute('app_issue_view', ['id' => $issue->getId()]);
        }
    }
    /**
     * @Route("/delete/{id}", name="app_issue_delete",
     *     requirements={"id"="\d+"})
     */
    public function deleteAction($id)
    {
        if ($this->get('issues.model.issue_deletion')->deleteIssueById($id)) {
            $this->addFlash(
                'success',
                $this->get('translator')
                    ->trans('issues.issue.flashMessages.delete.success')
            );
        }

        return $this->redirectToRoute('issues.issues_index');
    }

    /**
     * @Route("/add-issue-dialog/{id}", name="app_issue_add_dialog",
     *     requirements={"id"="\d+"})
     * @Template
     */
    public function createIssueForUserAction(User $assignee, Request $request)
    {
        $currentUser = $this->get('security.context')->getToken()->getUser();

        $issue = new Issue();
        $issue->setAsignee($assignee);
        $issue->setOwner($currentUser);

        /**
         * @var Form
         */
        $form = $this->createForm(new IssueType(), $issue);

        $saved = false;

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /**
                 * @var Issue
                 */
                $issue = $form->getData();

                $issue->setReporter($currentUser);

                $em = $this->getDoctrine()->getManager();

                $em->persist($issue);
                $em->flush();

                if ($issue->getId()) {
                    $saved = true;
                }
            }
        }

        return [
            'form' => $form->createView(),
            'saved' => $saved,
        ];
    }
}
