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

    /**
     * @param Issue $issue
     * @param Request $request
     * @param null $parent
     * @return array
     */
    private function update(Issue $issue, Request $request, $parent = null)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new IssueType(), $issue);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /*
             * @var Issue
             */
            $data = $form->getData();
            if ($parent instanceof Issue) {
                $data->setParent($parent);
                $data->setType(Issue::TYPE_SUBTASK);
            }
            $em->persist($data);
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


    /**
     *
     * @Route("/view/{id}", name="app_issue_view", requirements={"id"="\d+"})
     * @Template("IssueBundle:Issue:view.html.twig")
     */
    public function viewAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IssueBundle:Issue')->find($id);
        $editRoute = $this->generateUrl('app_issue_update', ['id'=>$id]);
        return ['entity'=>$entity, 'editRoute'=>$editRoute];
    }



    /**
     * @Route("/create/{id}/subtask", name="app_issue_create_subtask", requirements={"id"="\d+"})
     * @Template("IssueBundle:Issue:update.html.twig")
     */
    public function createSubtaskAction(Issue $issue, Request $request)
    {
        $subtask = new Issue();
        $subtask->setParent($issue);
        $subtask->setType(Issue::TYPE_SUBTASK);

        return $this->update($subtask, $request, $issue);
    }

    /**
     * @Route("/chart", name="app_issue_dashboard_chart_widget")
     * @Template("IssueBundle:Dashboard:chart_issues_types_widget.html.twig")
     */
    public function dashboardChartWidgetAction()
    {
        $items = $this->getDoctrine()->getRepository('App/IssueBundle/Entity/Issue')->getIssuesCountGruppedByStatus();
        var_dump($items);
        die();

        $viewBuilder = $this->container->get('oro_chart.view_builder');

        $view = $viewBuilder
            ->setOptions(array('name' => 'line_chart'))
            ->setArrayData($items)
            ->setDataMapping(array('label' => 'firstName', 'value' => 'fee'))
            ->getView();

        return ['chartView' => $view];
    }
}
