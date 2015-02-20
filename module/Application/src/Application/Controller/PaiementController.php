<?php

namespace Application\Controller;

use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Json\Json;

/**
 * @method \Application\Controller\Plugin\Context     context()
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PaiementController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')
                ->disableForEntity('Application\Entity\Db\ElementPedagogique')
                ->disableForEntity('Application\Entity\Db\Etape')
                ->disableForEntity('Application\Entity\Db\Etablissement')
                ->disableForEntity('Application\Entity\Db\FonctionReferentiel');
    }

    public function indexAction()
    {
        return [];
    }

    public function demandeMiseEnPaiementAction()
    {
        $this->initFilters();
        $intervenant        = $this->context()->mandatory()->intervenantFromRoute(); /* @var $intervenant \Application\Entity\Db\Intervenant */
        $annee              = $this->context()->getGlobalContext()->getAnnee();
        if ($this->getRequest()->isPost()) {
            $changements = $this->params()->fromPost('changements', '{}');
            $changements = Json::decode($changements, Json::TYPE_ARRAY);
            //var_dump($changements);
            $this->getServiceMiseEnPaiement()->saveChangements($changements);
        }
        $servicesAPayer     = $this->getServiceServiceAPayer()->getListByIntervenant($intervenant, $annee);
        return compact('intervenant', 'servicesAPayer');
    }

    public function etatPaiementAction()
    {
        $this->initFilters();

        $etat = $this->params()->fromRoute('etat');

        $rechercheForm = $this->getFormMiseEnPaiementRecherche();
        $recherche = new \Application\Entity\Paiement\MiseEnPaiementRecherche;
        $recherche->setEtat( $etat ); // données à mettre en paiement uniquement
        $rechercheForm->bind($recherche);

        $qb = $this->getServiceStructure()->finderByMiseEnPaiement();
        $this->getServiceMiseEnPaiement()->finderByEtat($etat, $qb);
        $structures = $this->getServiceStructure()->getList($qb);
        $rechercheForm->populateStructures( $structures );

        if (count($structures) == 1){
            $structure = current($structures);
            $rechercheForm->get('structure')->setValue( $structure->getId() );
        }else{
            $structure = $this->context()->structureFromPost(); /* @var $structure \Application\Entity\Db\Structure */
        }

        $periode = null;
        if ($structure){
            $qb = $this->getServicePeriode()->finderByMiseEnPaiement($structure);
            $this->getServiceMiseEnPaiement()->finderByEtat($etat, $qb);
            $periodes = $this->getServicePeriode()->getList($qb);
            $rechercheForm->populatePeriodes( $periodes );

            if (count($periodes) == 1){
                $periode = current($periodes);
                $rechercheForm->get('periode')->setValue( $periode->getId() );
            }else{
                $periode = $this->context()->periodeFromPost(); /* @var $periode \Application\Entity\Db\Periode */
            }

            $qb = $this->getServiceIntervenant()->finderByMiseEnPaiement( $structure, $periode );
            $this->getServiceMiseEnPaiement()->finderByEtat($etat, $qb);
            $rechercheForm->populateIntervenants( $this->getServiceIntervenant()->getList($qb) );
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $rechercheForm->setData($request->getPost());
            $rechercheForm->isValid();
        }

        $etatPaiement = null;
        if ( $recherche->getIntervenants()->count() > 0 ){
            $etatPaiement = $this->getServiceMiseEnPaiement()->getEtatPaiement( $recherche );
        }
        return compact( 'rechercheForm', 'etatPaiement', 'etat' );
    }

    public function miseEnPaiementAction()
    {
        $title = 'Mise en paiement';
        $structure    = $this->context()->mandatory()->structureFromRoute();
        $intervenants = $this->params('intervenants');

        $form = $this->getFormMiseEnPaiement();
        $errors = [];
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->isValid();

            $dateMiseEnPaiement = $form->get('date-mise-en-paiement')->getValue();
            $periode            = $form->get('periode')->getValue();

            $dateMiseEnPaiement = \DateTime::createFromFormat('d/m/Y', $dateMiseEnPaiement);
            $periode = $this->getServicePeriode()->get($periode);

            $intervenants = $this->getServiceIntervenant()->get( explode(',',$intervenants) );
            try{
                $this->getServiceMiseEnPaiement()->mettreEnPaiement($structure, $intervenants, $periode, $dateMiseEnPaiement);
            }catch(\Exception $e){
                $errors[] = $e->getMessage();
            }
        }

        return compact('form','title', 'errors');
    }

    /**
     * @return \Application\Form\Paiement\MiseEnPaiementForm
     */
    protected function getFormMiseEnPaiement()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('PaiementMiseEnPaiementForm');
    }

    /**
     * @return \Application\Form\Paiement\MiseEnPaiementRechercheForm
     */
    protected function getFormMiseEnPaiementRecherche()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('PaiementMiseEnPaiementRechercheForm');
    }

    /**
     * @return \Application\Service\Intervenant
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('applicationIntervenant');
    }

    /**
     * @return \Application\Service\Periode
     */
    protected function getServicePeriode()
    {
        return $this->getServiceLocator()->get('applicationPeriode');
    }

    /**
     * @return \Application\Service\Structure
     */
    protected function getServiceStructure()
    {
        return $this->getServiceLocator()->get('applicationStructure');
    }

    /**
     * @return \Application\Service\MiseEnPaiement
     */
    protected function getServiceMiseEnPaiement()
    {
        return $this->getServiceLocator()->get('applicationMiseEnPaiement');
    }

    /**
     * @return \Application\Service\ServiceAPayer
     */
    protected function getServiceServiceAPayer()
    {
        return $this->getServiceLocator()->get('applicationServiceAPayer');
    }
}