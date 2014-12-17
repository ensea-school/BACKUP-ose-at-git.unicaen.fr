<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Opérations autour des notifications.
 *
 * @method \Doctrine\ORM\EntityManager em()
 * @method Plugin\Context              context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NotifController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     * Page vide invitant à sélectionner un type d'agrément dans le menu.
     * 
     * @return array
     */
    public function indexAction()
    {
        $this->title = sprintf("Agréments %s", $this->intervenant ? "<small>{$this->intervenant}</small>" : null);

        return ['title' => $this->title];
    }
}