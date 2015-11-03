<?php

namespace Application\Controller\OffreFormation;

use Application\Entity\Db\DomaineFonctionnel;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeFormation;
use Application\Form\OffreFormation\Traits\EtapeSaisieAwareTrait;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\ElementPedagogiqueAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\NiveauEtapeAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Exception\DbException;

/**
 * Description of EtapeController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 *
 */
class EtapeController extends AbstractActionController
{
    use ContextAwareTrait;
    use ElementPedagogiqueAwareTrait;
    use EtapeAwareTrait;
    use NiveauEtapeAwareTrait;
    use EtapeSaisieAwareTrait;



    protected function saisirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            TypeFormation::class,
            DomaineFonctionnel::class,
            Structure::class,
        ]);

        $etape  = $this->getEvent()->getParam('etape');
        $title  = $etape ? "Modification d'une formation" : "Création d'une nouvelle formation";
        $form   = $this->getFormOffreFormationEtapeSaisie();
        $errors = [];

        if ($etape) {
            $form->bind($etape);
        } else {
            $etape = $this->getServiceEtape()->newEntity();
            $form->setObject($etape);
        }

        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceEtape()->save($etape);
                    $form->get('id')->setValue($etape->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }
        }

        return compact('form', 'title', 'errors');
    }



    public function supprimerAction()
    {
        if (!($etape = $this->getEvent()->getParam('etape'))) {
            throw new \Common\Exception\RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }
        $title  = "Suppression de formation";
        $form   = new \Application\Form\Supprimer('suppr');
        $errors = [];
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        if ($this->getRequest()->isPost()) {
            try {
                $this->getServiceEtape()->delete($etape);
            } catch (\Exception $e) {
                $e        = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
        }

        return compact('etape', 'title', 'form', 'errors');
    }



    public function voirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            ElementPedagogique::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            ElementPedagogique::class,
        ]);
        $etape        = $this->getEvent()->getParam('etape');
        $title        = 'Formation';
        $serviceEtape = $this->getServiceEtape();

        return compact('etape', 'title', 'serviceEtape');
    }
}
