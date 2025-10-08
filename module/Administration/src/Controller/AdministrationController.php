<?php

namespace Administration\Controller;


use Application\Controller\AbstractController;
use Framework\Navigation\Navigation;

/**
 * Description of AdministrationController
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AdministrationController extends AbstractController
{

    public function __construct(
        private readonly Navigation $navigation
    )
    {
    }

    public function indexAction()
    {
        $pages = $this->navigation->getCurrentPage()->getVisiblePages();

        return compact('pages');
    }



    public function rubriqueAction()
    {
        $pages = $this->navigation->getCurrentPage()->getVisiblePages();

        return compact('pages');
    }

}