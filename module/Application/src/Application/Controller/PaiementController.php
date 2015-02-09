<?php

namespace Application\Controller;

use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Entity\Db\FormuleResultatService;
use Application\Entity\Db\FormuleResultatServiceReferentiel;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Annee;

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

    protected function getHeuresAPayer( Intervenant $intervenant, Annee $annee )
    {
        $typeVolumeHoraire  = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $etatVolumeHoraire  = $this->getServiceEtatVolumeHoraire()->getSaisi();

        $frsList = $intervenant
                        ->getUniqueFormuleResultat($annee, $typeVolumeHoraire, $etatVolumeHoraire)
                        ->getFormuleResultatService()->filter(
        function( FormuleResultatService $formuleResultatService ){
            $totalHC = $formuleResultatService->getHeuresComplFa()
                     + $formuleResultatService->getHeuresComplFc()
                     + $formuleResultatService->getHeuresComplFcMajorees()
                     + $formuleResultatService->getHeuresComplFi();
            return $totalHC > 0;
        });

        $frsrList = $intervenant
                        ->getUniqueFormuleResultat($annee, $typeVolumeHoraire, $etatVolumeHoraire)
                        ->getFormuleResultatServiceReferentiel()->filter(
        function( FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel ){
            $totalHC = $formuleResultatServiceReferentiel->getHeuresComplReferentiel();
            return $totalHC > 0;
        });

        return [
            'service'       => $frsList,
            'referentiel'   => $frsrList,
        ];
    }

    /**
     *
     * @return type
     */
    public function indexAction()
    {
        return [];
    }

    public function miseEnPaiementAction()
    {
        $this->initFilters();

        /* Initialisation des données... */
        $annee              = $this->context()->getGlobalContext()->getAnnee();
        $intervenant        = $this->context()->mandatory()->intervenantFromRoute();
        /* @var $intervenant \Application\Entity\Db\Intervenant */
        $hap = $this->getHeuresAPayer($intervenant, $annee);
        $services = $hap['service'];
        $referentiels = $hap['referentiel'];

        return compact('intervenant', 'services', 'referentiels');
    }

    public function miseEnPaiementSaisieAction()
    {
        $form = $this->getMiseEnPaiementSaisieForm();
        $errors = [];
        

        $terminal = $this->getRequest()->isXmlHttpRequest();
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/paiement/mise-en-paiement-saisie')
                ->setVariables(compact('form', 'errors'));
        if ($terminal) {
            return $this->popoverInnerViewModel($viewModel, "Mise en paiement", false);
        }
        return $viewModel;

    }

    public function centreCoutRechercheAction()
    {
        var_dump('coucou');
    }

    /**
     * Retourne le formulaire de modif de Volume Horaire.
     *
     * @return \Application\Form\Paiement\MiseEnPaiementSaisieForm
     */
    protected function getMiseEnPaiementSaisieForm()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('MiseEnPaiementSaisie');
    }

    /**
     * @return \Application\Service\TypeVolumeHoraire
     */
    protected function getServiceTypeVolumeHoraire()
    {
        return $this->getServiceLocator()->get('applicationTypeVolumeHoraire');
    }

    /**
     * @return \Application\Service\EtatVolumeHoraire
     */
    protected function getServiceEtatVolumeHoraire()
    {
        return $this->getServiceLocator()->get('applicationEtatVolumeHoraire');
    }
}