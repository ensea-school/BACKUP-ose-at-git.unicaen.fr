<?php

namespace Application\Controller\OffreFormation;

use Application\Controller\AbstractController;
use Application\Entity\Db\CheminPedagogique;
use Application\Entity\Db\DomaineFonctionnel;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeFormation;
use Application\Form\OffreFormation\TauxMixite\Traits\TauxMixiteFormAwareTrait;
use Application\Form\OffreFormation\Traits\EtapeSaisieAwareTrait;
use Application\Service\Traits\CheminPedagogiqueServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\EtapeServiceAwareTrait;
use Application\Service\Traits\NiveauEtapeServiceAwareTrait;

/**
 * Description of EtapeController
 *
 */
class EtapeController extends AbstractController
{
    use ContextServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use EtapeServiceAwareTrait;
    use NiveauEtapeServiceAwareTrait;
    use EtapeSaisieAwareTrait;
    use TauxMixiteFormAwareTrait;
    use CheminPedagogiqueServiceAwareTrait;


    protected function saisirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            TypeFormation::class,
            DomaineFonctionnel::class,
            Structure::class,
        ]);

        $structureId = $this->params()->fromRoute('structure');
        if ($structureId) {
            $structure = $this->em()->find(Structure::class, $structureId);
        } else {
            $structure = null;
        }
        $etape = $this->getEvent()->getParam('etape');

        $title  = $etape ? "Modification d'une formation" : "Création d'une nouvelle formation";
        $form   = $this->getFormOffreFormationEtapeSaisie();
        $errors = [];

        if (!$etape) {
            $etape = $this->getServiceEtape()->newEntity();
            $etape->setStructure($structure);
        }

        $form->bindRequestSave($etape, $this->getRequest(), function (Etape $etape) use ($form) {
            $this->getServiceEtape()->save($etape);
            $form->get('id')->setValue($etape->getId()); // transmet le nouvel ID
        });

        return compact('form', 'title');
    }



    public function supprimerAction()
    {
        if (!($etape = $this->getEvent()->getParam('etape'))) {
            throw new \RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }
        $title = "Suppression de formation";
        $form  = $this->makeFormSupprimer(function () use ($etape) {
            $this->getServiceEtape()->delete($etape);
        });

        return compact('etape', 'title', 'form');
    }



    public function restaurerAction()
    {
        if (!($etape = $this->getEvent()->getParam('etape'))) {
            throw new \RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }

        $etape->dehistoriser();
        $this->getServiceEtape()->save($etape);

        $elems = $this->em()->getRepository(ElementPedagogique::class)->findBy([
            'etape'            => $etape,
            'histoDestruction' => null,
        ]);
        foreach ($elems as $elem) {
            $entity = $this->getServiceCheminPedagogique()->newEntity();
            /* @see CheminPedagogique $entity */
            $entity->setEtape($etape);
            $entity->setElementPedagogique($elem);
            $this->getServiceCheminPedagogique()->save($entity);
        }

        return $this->redirect()->toRoute('of', ['etape' => $etape->getId()], [], true);
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
        $title        = $etape . ' (' . $etape->getCode() . ')';
        $serviceEtape = $this->getServiceEtape();

        return compact('etape', 'title', 'serviceEtape');
    }



    public function tauxMixiteAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\ElementPedagogique::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            \Application\Entity\Db\ElementPedagogique::class,
        ]);

        $etape = $this->getEvent()->getParam('etape');
        /* @var $etape Etape */
        $form = $this->getFormOffreFormationTauxMixiteTauxMixite();

        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->bind($etape);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->em()->flush();
                $form->bind($etape); // on re-binde pour forcer la MAJ
            } else {
                $this->flashMessenger()->addErrorMessage('La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.');
            }
        }
        $title = "Paramétrage des taux de mixité <br /><small>$etape</small>";

        return compact('etape', 'title', 'form');
    }
}
