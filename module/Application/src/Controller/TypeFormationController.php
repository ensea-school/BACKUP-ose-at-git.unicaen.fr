<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Controller;


use Application\Entity\Db\GroupeTypeFormation;
use Application\Entity\Db\TypeFormation;
use Application\Form\GroupeTypeFormation\Traits\GroupeTypeFormationSaisieFormAwareTrait;
use Application\Form\TypeFormation\Traits\TypeFormationSaisieFormAwareTrait;
use Application\Service\Traits\GroupeTypeFormationServiceAwareTrait;
use Application\Service\Traits\TypeFormationServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;


/**
 * Description of TypeFormationController
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class TypeFormationController extends AbstractController
{
    use EntityManagerAwareTrait;
    use TypeFormationSaisieFormAwareTrait;
    use TypeFormationServiceAwareTrait;
    use GroupeTypeFormationSaisieFormAwareTrait;
    use GroupeTypeFormationServiceAwareTrait;

    public function indexAction()
    {
        $dql                  = "SELECT gtf,tf FROM " . GroupeTypeFormation::class . " gtf 
            LEFT JOIN gtf.typeFormation tf WITH tf.histoDestruction is null
            WHERE gtf.histoDestruction is null
            ORDER BY gtf.ordre";
        $query                = $this->em()->createQuery($dql);
        $groupeTypeFormations = $query->getResult();

        $dqlWithoutGroup             = "SELECT tf FROM " . TypeFormation::class . " tf 
            WHERE tf.histoDestruction is null AND tf.groupe is null";
        $queryWithoutGroup           = $this->em()->createQuery($dqlWithoutGroup);
        $typeFormationsWithoutGroupe = $queryWithoutGroup->getResult();

        return compact('groupeTypeFormations', 'typeFormationsWithoutGroupe');
    }



    public function saisieAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            GroupeTypeFormation::class,
        ]);

        $typeFormation = $this->getEvent()->getParam('typeFormation');

        $form = $this->getFormTypeFormationTypeFormationSaisie();

        if (empty($typeFormation)) {
            $title = "Création d'un nouveau type de formation";
            /** @var TypeFormation $typeFormation */
            $typeFormation       = $this->getServiceTypeFormation()->newEntity();
            $groupeTypeFormation = $this->getEvent()->getParam('groupeTypeFormation');
            $typeFormation->setGroupe($groupeTypeFormation);
        } else {
            $title = "Édition d'un type de formation";
        }

        $form->bindRequestSave($typeFormation, $this->getRequest(), function () use ($typeFormation, $form) {

            $this->getServiceTypeFormation()->save($typeFormation);
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form', 'title');
    }



    public function ajoutAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            GroupeTypeFormation::class,
        ]);


        $form  = $this->getFormTypeFormationTypeFormationSaisie();
        $title = "Création d'un nouveau type de formation";
        /** @var TypeFormation $typeFormation */
        $typeFormation       = $this->getServiceTypeFormation()->newEntity();
        $groupeTypeFormation = $this->getEvent()->getParam('groupeTypeFormation');
        $typeFormation->setGroupe($groupeTypeFormation);

        $form->bindRequestSave($typeFormation, $this->getRequest(), function () use ($typeFormation, $form) {

            $this->getServiceTypeFormation()->save($typeFormation);
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form', 'title');
    }



    public function saisieGroupeAction()
    {
        $groupeTypeFormation = $this->getEvent()->getParam('groupeTypeFormation');
        $form                = $this->getFormGroupeTypeFormationGroupeTypeFormationSaisie();

        if (empty($groupeTypeFormation)) {
            $title               = "Création d'un nouveau groupe";
            $groupeTypeFormation = $this->getServiceGroupeTypeFormation()->newEntity();
        } else {
            $title = "Édition d'un groupe";
        }
        $form->bindRequestSave($groupeTypeFormation, $this->getRequest(), function () use ($groupeTypeFormation, $form) {

            $this->getServiceGroupeTypeFormation()->save($groupeTypeFormation);
            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form', 'title');
    }



    public function supprimerAction()
    {
        $typeformation = $this->getEvent()->getParam('typeFormation');
        $this->getServiceTypeFormation()->delete($typeformation, true);

        return new MessengerViewModel();
    }



    public function supprimerGroupeAction()
    {
        $groupetypeformation = $this->getEvent()->getParam('groupeTypeFormation');
        $this->getServiceGroupeTypeFormation()->delete($groupetypeformation, false);

        return new MessengerViewModel();
    }



    public function trierAction()
    {
        $champsIds = explode(',', $this->params()->fromPost('champsIds', ''));
        $ordre     = 1;

        foreach ($champsIds as $champId) {
            $sp = $this->getServiceGroupeTypeFormation()->get($champId);
            if ($sp) {
                $sp->setOrdre($ordre);
                $ordre++;
                $this->getServiceGroupeTypeFormation()->save($sp);
            }
        }

        return new MessengerViewModel();
    }

}