<?php
/**
 * Created by PhpStorm.
 * User: mgz
 * Date: 09.09.16
 * Time: 08:47
 */

namespace App\UserBundle\Controller;


use Oro\Bundle\UserBundle\Entity\User;
use App\IssueBundle\Entity\Issue;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oro\Bundle\UserBundle\Controller\UserController;

class UserController extends UserController
{
    /**
     * @Route("/widget/issues/{id}", name="app_user_widget_issue", requirements={"id"="\d+"})
     * @Template
     */
    public function issueAction(User $user)
    {
        $issues = array();
        return array(
            'entity'      => $user,
            'issues'      => $issues,
            'userApi'     => $this->getUserApi($user),
            'viewProfile' => (bool)$this->getRequest()->query->get('viewProfile', false)
        );
    }

}