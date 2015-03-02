<?php

namespace Application\Controller;

use Application\Acl\ComposanteRole;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TypeContrat;
use Application\Entity\Db\TypeValidation;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Common\Constants;
use Common\Exception\LogicException;
use UnicaenApp\Exporter\Pdf;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\Intervenant\ContratValidation;
use Application\Form\Intervenant\ContratRetour;
use Application\Entity\Db\Contrat;
use Application\Assertion\ContratAssertion;
use Application\Assertion\FichierAssertion;
use Zend\View\Model\JsonModel;
use BjyAuthorize\Exception\UnAuthorizedException;

/**
 * Description of ContratController
 *
 * @method Application\Controller\Plugin\Context context()
 * @method Application\Controller\Plugin\Upload\UploadPlugin uploader()
 * @method \Doctrine\ORM\EntityManager em()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;
    
    /**
     * @var Contrat
     */
    private $contrat;
    
    /**
     * 
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')
                ->disableForEntity('Application\Entity\Db\ElementPedagogique')
                ->disableForEntity('Application\Entity\Db\Etape');
    }
    
    /**
     * Point d'entrée sur les contrats/avenants.
     * 
     * @return array
     */
    public function indexAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof IntervenantRole) {
            $intervenant = $role->getIntervenant();
        }
        else {
            $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }
        
        if ($intervenant instanceof \Application\Entity\Db\IntervenantPermanent) {
            throw new \Common\Exception\MessageException("Les intervenants permanents n'ont pas de contrat.");
        }
        
        if ($role instanceof ComposanteRole) {
            return $this->creerAction();
        }
        else {
            return $this->voirAction();
        }
    }
    
    /**
     * 
     * @return array
     */
    public function voirAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        $this->initFilters();
        
        // fetch (projets de) contrats/avenants
        $contrats = $this->getContrats();
        // fetch des services associés
        $services = $this->getServicesContrats($contrats);

        $this->getView()->setVariables(array(
            'role'        => $role,
            'contrats'    => $contrats,
            'services'    => $services,
            'intervenant' => $this->getIntervenant(),
        ));
        $this->getView()->setTemplate('application/contrat/voir');
        
        return $this->getView();
    }
    
    /**
     * Fetch des (projets de) contrats/avenants de l'intervenant.
     * 
     * @return Contrat[]
     */
    private function getContrats()
    {
        $role           = $this->getContextProvider()->getSelectedIdentityRole();
        $serviceContrat = $this->getServiceContrat();
        $structure      = $role instanceof ComposanteRole ? $role->getStructure() : null;
        
        $qb = $serviceContrat->finderByIntervenant($this->getIntervenant());
        if ($structure) {
            $serviceContrat->finderByStructure($structure, $qb);
        }
        $alias = $serviceContrat->getAlias();
        $qb->addOrderBy("$alias.typeContrat")->addOrderBy("$alias.numeroAvenant");
        $contrats = $qb->getQuery()->getResult();
        
        return $contrats;
    }
    
    /**
     * Fetch des services concernés par des contrats.
     * 
     * @param array $contrats
     * @return array [ Id Contrat => [ Id Service => Service ] ]
     */
    private function getServicesContrats($contrats)
    {
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $services          = [];
        
        foreach ($contrats as $contrat) { /* @var $contrat \Application\Entity\Db\Contrat */ 
            $qb = $this->getServiceService()->getRepo()->createQueryBuilder("s")
                    ->select("s, vh, str, i")
                    ->join("s.volumeHoraire", "vh")
                    ->join("s.structureEns", "str")
                    ->join("s.intervenant", "i")
                    ->andWhere("vh.contrat = :contrat")->setParameter("contrat", $contrat);
            $query = $qb->getQuery();
            foreach ($query->execute() as $service) { /* @var $service \Application\Entity\Db\Service */
                $this->em()->detach($service); // INDISPENSABLE si on requête N fois la même entité avec des critères différents
//                if (0 == $service->getVolumeHoraireListe()->getHeures()) {
//                    continue;
//                }
                $services[$contrat->getId()][$service->getId()] = $service;
                $service->setTypeVolumehoraire($typeVolumeHoraire); // pour aide de vue! :-(
            }
        }
        
        return $services;
    }
    
    /**
     * Fetch des services à récapituler sur un contrat, c'est-à-dire
     * les services du contrat en question + tous les services
     * des contrats/avenants précédemment créés.
     * 
     * @param Contrat $contrat Contrat concerné
     * @return Service[]
     */
    private function getServicesRecapsContrat(Contrat $contrat)
    {
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        
        $this->em()->clear('Application\Entity\Db\Service'); // indispensable si on requête N fois la même entité avec des critères différents
        $qb = $this->getServiceService()->getRepo()->createQueryBuilder("s")
                ->select("s, vh, str, i")
                ->join("s.volumeHoraire", "vh")
                ->join("s.structureEns", "str")
                ->join("s.intervenant", "i")
                ->join("vh.contrat", "c")
                ->andWhere("c.histoCreation <= :date")->setParameter("date", $contrat->getHistoModification())
                ->andWhere("i = :intervenant")->setParameter("intervenant", $contrat->getIntervenant())
                ->andWhere("str = :structure")->setParameter("structure", $contrat->getStructure());
        $services = [];
        foreach ($qb->getQuery()->getResult() as $service) {
            if (0 == $service->getVolumeHoraireListe()->getHeures()) {
                continue;
            }
            $services[$service->getId()] = $service;
        }
        
        $this->getServiceService()->setTypeVolumehoraire($services, $typeVolumeHoraire); // pour aide de vue! :-(
        
        return $services;
    }
    
    private $contratProcess;
    
    /**
     * @return \Application\Service\Process\ContratProcess
     */
    private function getProcessContrat()
    {
        if (null === $this->contratProcess) {
            $this->contratProcess = $this->getServiceLocator()->get('ApplicationContratProcess');
            $this->contratProcess->setIntervenant($this->getIntervenant());
        }
        
        return $this->contratProcess;
    }
    
    public function creerAction()
    {
        $this->voirAction();
        
        $role             = $this->getContextProvider()->getSelectedIdentityRole();
        $process          = $this->getProcessContrat();
        $peutCreerContrat = $process->getPeutCreerContratInitial();
        $peutCreerAvenant = $process->getPeutCreerAvenant();
        $messages         = [];
        $servicesDispos   = null;
        $action           = null;
        
        // instanciation d'un contrat fictif transmis à l'assertion
        $contrat = $this->getServiceContrat()->newEntity(TypeContrat::CODE_CONTRAT)
                ->setIntervenant($this->getIntervenant())
                ->setStructure($this->getStructure());
        if (!$this->isAllowed($contrat, ContratAssertion::PRIVILEGE_CREATE)) {
            throw new \Common\Exception\MessageException("La création de contrat/avenant n'est pas encore possible.");
        }
        
        if ($peutCreerContrat) {
            $servicesDispos = $process->getServicesDisposPourContrat();
            $action = "Créer le projet de contrat";
        }
        elseif ($peutCreerAvenant) {
            $servicesDispos = $process->getServicesDisposPourAvenant();
            $action = "Créer le projet d'avenant";
            if (($validation = $process->getValidationContratInitial())) { /* @var $validation \Application\Entity\Db\Validation */
                $messages['info'] = sprintf("Pour information, des enseignements de %s au sein de la composante &laquo; %s &raquo; ont fait l'objet d'un contrat validé le %s par %s.",
                        $this->getIntervenant(), 
                        $validation->getStructure(),
                        $validation->getHistoModification()->format(Constants::DATETIME_FORMAT),
                        $validation->getHistoModificateur());
            }
        }
        
        if ($servicesDispos) {
            $this->getServiceService()->setTypeVolumehoraire($servicesDispos, $this->getServiceTypeVolumeHoraire()->getPrevu()); // aide de vue
            $messages['info'] = "Des enseignements validés candidats pour un contrat/avenant ont été trouvés.";
        }
//        else {
//            $messages['info'] = "Aucun enseignement validé candidat pour un contrat/avenant n'a été trouvé.";
//        }
            
        if ($this->getRequest()->isPost() && ($peutCreerContrat || $peutCreerAvenant)) {
            $process->creer();
            $this->flashMessenger()->addSuccessMessage($process->getMessages()[0]);
            
            return $this->redirect()->toRoute('intervenant/contrat', array('intervenant' => $this->getIntervenant()->getSourceCode()));
        }
        
        $this->getView()->setVariables(array(
            'role'           => $role,
            'title'          => "Contrat/avenants pour la structure &laquo; {$this->getStructure()} &raquo; <small>{$this->getIntervenant()}</small>",
            'intervenant'    => $this->getIntervenant(),
            'servicesDispos' => $servicesDispos,
            'messages'       => $messages,
            'action'         => $action,
        ));
            
        $this->getView()->setTemplate('application/contrat/creer');
        
        return $this->getView();
    }
    
    /**
     * Suppression d'un projet de contrat/avenant par la composante d'intervention.
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws Common\Exception\MessageException
     */
    public function supprimerAction()
    {
        $this->contrat   = $this->context()->mandatory()->contratFromRoute();
        $contratToString = lcfirst($this->contrat->toString(true, true));
        
//        $rule = new \Application\Rule\Intervenant\PeutValiderContratRule($this->intervenant, $this->contrat);
//        if (!$rule->execute()) {
//            throw new \Common\Exception\MessageException("Impossible de valider $contratToString.", null, new \Exception($rule->getMessage()));
//        }
        if (!$this->isAllowed($this->contrat, ContratAssertion::PRIVILEGE_DELETE)) {
            throw new \Common\Exception\MessageException("La suppression $contratToString n'est pas possible.");
        }
        
        $result = $this->confirm()->execute();

        if (is_array($result)) {
            // confirmation postée
            try {
                $this->getServiceContrat()->supprimer($this->contrat); 
                $this->flashMessenger()->addSuccessMessage("Suppression $contratToString effectuée avec succès.");
            }
            catch(\Exception $e) {
                $e = \Application\Exception\DbException::translate($e);
                $this->confirm()->setMessages([$e->getMessage()]);
            }
        }
        
        $viewModel = $this->confirm()->getViewModel();
        
        $viewModel->setVariables([
            'title'   => "Suppression $contratToString",
            'contrat' => $this->contrat,
        ]);
        
        return $viewModel;
    }
    
    /**
     * Validation du contrat/avenant par la composante d'intervention.
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws Common\Exception\MessageException
     */
    public function validerAction()
    {
        $role              = $this->getContextProvider()->getSelectedIdentityRole();
        $this->structure   = $role->getStructure();
        $this->contrat     = $this->context()->mandatory()->contratFromRoute();
        $this->intervenant = $this->contrat->getIntervenant();
        $form              = $this->getFormValidationContrat()->setContrat($this->contrat)->init();
        $contratToString   = $this->contrat->toString(true);
        $title             = "Validation $contratToString <small>$this->intervenant</small>";
        $process           = $this->getProcessContrat();
        $messages          = [];
        
        $rule = new \Application\Rule\Intervenant\PeutValiderContratRule($this->intervenant, $this->contrat);
        if (!$rule->execute()) {
            throw new \Common\Exception\MessageException("Impossible de valider $contratToString.", null, new \Exception($rule->getMessage()));
        }

        $this->em()->getFilters()->enable('historique');
        
        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));
        
        // recherche s'il existe déjà un contrat validé (qqsoit la composante), auquel cas le projet de contrat
        // sera requalifié en avenant
        $requalifier = false;
        if ($process->getDeviendraAvenant($this->contrat)) {
            $requalifier = true;
            $message = "<p><strong>NB :</strong> à l'issue de sa validation, " . lcfirst($this->contrat->toString(true)) . 
                    " deviendra un avenant car un contrat a déjà été validé par une autre composante.</p>" .
                    "<p><strong>Vous devrez donc impérativement imprimer à nouveau le document !</strong></p>";
            $messages = ['warning' => $message];
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getServiceContrat()->requalifier($this->contrat); // requalification SI BESOIN
                $this->validation = $this->getServiceContrat()->valider($this->contrat);
                
                $this->flashMessenger()->addSuccessMessage(
                        "Validation " . lcfirst($this->contrat->toString(true, true)) . " enregistrée avec succès.");
            }
        }
        
        $this->view = new \Zend\View\Model\ViewModel(array(
            'role'        => $role,
            'intervenant' => $this->intervenant,
            'form'        => $form,
            'title'       => $title,
            'messages'    => $messages,
        ));
        $this->view->setTemplate('application/validation/contrat');
        
        return $this->view;
    }
    
    /**
     * Dévalidation du contrat/avenant par la composante d'intervention.
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws Common\Exception\MessageException
     */
    public function devaliderAction()
    {
        $this->contrat     = $this->context()->mandatory()->contratFromRoute();
        $this->intervenant = $this->contrat->getIntervenant();
        $this->validation  = $this->contrat->getValidation();
        $contratToString   = lcfirst($this->contrat->toString(true, true));
        
        $rule = new \Application\Rule\Intervenant\PeutDevaliderContratRule($this->intervenant, $this->contrat);
        $rule->setContratService($this->getServiceContrat());
        if (!$rule->execute()) {
            throw new \Common\Exception\MessageException(
                    "Impossible de supprimer la validation $contratToString.", 
                    null, 
                    new \Exception($rule->getMessage()));
        }
        
        // suppression de la validation déléguée au contrôleur dédié
        $controller       = 'Application\Controller\Validation';
        $params           = $this->getEvent()->getRouteMatch()->getParams();
        $params['action'] = 'supprimer';
        $params['validation'] = $this->validation->getId();
        $viewModel        = $this->forward()->dispatch($controller, $params); /* @var $viewModel \Zend\View\Model\ViewModel */

        if ($this->getRequest()->isPost()) {
            $this->getServiceContrat()->devalider($this->contrat);

            $this->flashMessenger()->clearMessages()->addSuccessMessage(
                    "Validation " . lcfirst($this->contrat->toString(true, true)) . " supprimée avec succès.");
        }
        
        return $viewModel;
    }
    
    /**
     * Saisie de la date de retour du contrat/avenant signé par l'intervenant.
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws Common\Exception\MessageException
     */
    public function saisirRetourAction()
    {
        $role              = $this->getContextProvider()->getSelectedIdentityRole();
        $this->contrat     = $this->context()->mandatory()->contratFromRoute();
        $this->intervenant = $this->contrat->getIntervenant();
        $form              = $this->getFormRetourContrat()->setContrat($this->contrat)->init();
        $contratToString   = $this->contrat->toString(true, true);
        $title             = "Retour $contratToString signé <small>$this->intervenant</small>";
        $process           = $this->getProcessContrat();
        $messages          = [];
        
        $rule = new \Application\Rule\Intervenant\PeutSaisirRetourContratRule($this->intervenant, $this->contrat);
        if (!$rule->execute()) {
            throw new \Common\Exception\MessageException(
                    "Impossible de saisir la date de retour $contratToString.", 
                    null, 
                    new \Exception($rule->getMessage()));
        }
        
        $form   ->bind($this->contrat)
                ->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->em()->flush();
                
                $this->flashMessenger()->addSuccessMessage(
                        "Saisie du retour $contratToString signé enregistrée avec succès.");
            }
        }
        
        $this->view = new \Zend\View\Model\ViewModel(array(
            'role'        => $role,
            'intervenant' => $this->intervenant,
            'form'        => $form,
            'title'       => $title,
            'messages'    => $messages,
        ));
        
        return $this->view;
    }
    
    /**
     * 
     */
    public function exporterAction()
    {       
        $this->initFilters();
        
        // fetch le contrat/avenant spécifié
        $serviceContrat = $this->getServiceContrat();
        $qb = $serviceContrat->getRepo()->createQueryBuilder("c")
                ->select("c, i, vh")
                ->join("c.intervenant", "i")
                ->join("c.structure", "str")
                ->join("c.volumeHoraire", "vh"/*, \Doctrine\ORM\Query\Expr\Join::WITH, "vh.motifNonPaiement is null"*/)
                ->andWhere("c = :id")->setParameter('id', $this->params('contrat'))
                ->orderBy("str.libelleCourt");
        
        try {
            $this->contrat = $qb->getQuery()->getSingleResult();
        }
        catch (\Doctrine\ORM\NoResultException $nre) {
            throw new \Common\Exception\MessageException("Contrat/avenant spécifié introuvable.", null, $nre);
        }

        $this->intervenant = $this->contrat->getIntervenant();
        
        if (! $this->isAllowed($this->contrat, ContratAssertion::PRIVILEGE_READ)) {
            throw new UnAuthorizedException("Interdit !");
        }
        
        $annee           = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $estUnAvenant    = $this->contrat->estUnAvenant();
        $contratToString = (string) $this->contrat;
        $nomIntervenant  = (string) $this->intervenant;
        $dateNaissance   = $this->intervenant->getDateNaissanceToString();
        $estATV          = $this->intervenant->getStatut()->estAgentTemporaireVacataire();
        $estUnProjet     = $this->contrat->getValidation() ? false : true;
        $contratIniModif = $estUnAvenant && $this->contrat->getContrat()->getStructure() === $this->contrat->getStructure() ? true : false;
        $dateSignature   = $estUnProjet ? $this->contrat->getHistoCreation() : $this->contrat->getValidation()->getHistoCreation();
        $servicesRecaps  = $this->getServicesRecapsContrat($this->contrat); // récap de tous les services au sein de la structure d'ens
        $totalHETD       = $this->getTotalHetdIntervenant();
        
        if ($this->intervenant->getDossier()) {
            $adresseIntervenant    = $this->intervenant->getDossier()->getAdresse();
            $numeroINSEE           = $this->intervenant->getDossier()->getNumeroInsee();
            $nomCompletIntervenant = $this->intervenant->getDossier()->getCivilite() . ' ' . $nomIntervenant;
        }
        else {
            $adresseIntervenant    = $this->intervenant->getAdressePrincipale(true);
            $numeroINSEE           = $this->intervenant->getNumeroInsee() . ' ' . $this->intervenant->getNumeroInseeCle();
            $nomCompletIntervenant = $this->intervenant->getCivilite() . ' ' . $nomIntervenant;
        }
        
        $fileName = sprintf("contrat_%s_%s_%s.pdf", 
                $this->contrat->getStructure()->getSourceCode(), 
                $this->intervenant->getNomUsuel(), 
                $this->intervenant->getSourceCode());
        
        $variables = array(
            'estUnAvenant'            => $estUnAvenant,
            'estUnProjet'             => $estUnProjet,
            'contratIniModif'         => $contratIniModif,
            'etablissement'           => "L'université de Caen",
            'etablissementRepresente' => ", représentée par son Président, Pierre SINEUX",
            'nomIntervenant'          => $nomIntervenant,
            'f'                       => $this->intervenant->estUneFemme(),
            'dateNaissance'           => $dateNaissance,
            'adresseIntervenant'      => nl2br($adresseIntervenant),
            'numeroINSEE'             => $numeroINSEE,
            'estATV'                  => $estATV,
            'nomCompletIntervenant'   => $nomCompletIntervenant,
            'annee'                   => $annee,
            'dateSignature'           => $dateSignature->format(Constants::DATE_FORMAT),
            'lieuSignature'           => "Caen",
            'servicesRecaps'          => $servicesRecaps,
            'totalHETD'               => \UnicaenApp\Util::formattedFloat($totalHETD, \NumberFormatter::DECIMAL, 2),
        );

        // Création du pdf, complétion et envoi au navigateur
        $exp = new Pdf($this->getServiceLocator()->get('view_manager')->getRenderer());
        $exp    ->setHeaderSubtitle($contratToString)
                ->setMarginBottom(25)
                ->setMarginTop(25);
        if ($estUnProjet) {
            $exp->setWatermark("Projet");
        }
        
        $variables['mentionRetourner'] = "EXEMPLAIRE À CONSERVER";
        $exp->addBodyScript('application/contrat/contrat-pdf.phtml', false, $variables);
        
        $variables['mentionRetourner'] = "EXEMPLAIRE À RETOURNER SIGNÉ";
        $exp->addBodyScript('application/contrat/contrat-pdf.phtml', true, $variables, 1);

        $exp->export($fileName, Pdf::DESTINATION_BROWSER_FORCE_DL);
    }

    /**
     * @return float
     */
    public function getTotalHetdIntervenant()
    {   
        $annee             = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getValide();
        
        $fr = $this->intervenant->getUniqueFormuleResultat($annee, $typeVolumeHoraire, $etatVolumeHoraire);

        return $fr->getServiceAssure();
    }
    
    /**
     * Dépôt du contrat signé.
     * 
     * @return Response
     */
    public function deposerFichierAction()
    {
        $contrat = $this->context()->mandatory()->contratFromRoute();
        
        $result  = $this->uploader()->upload();
        
        if ($result instanceof JsonModel) {
            return $result;
        }
        if (is_array($result)) {
            $this->getServiceContrat()->creerFichiers($result['files'], $contrat);
        }
        
        return $this->redirect()->toRoute('contrat/lister-fichier', [], [], true);
    }
    
    /**
     * Listing des fichiers déposés pour le contrat.
     * 
     * @return aarray
     */
    public function listerFichierAction()
    {
        $contrat = $this->context()->mandatory()->contratFromRoute();
               
        return [
            'contrat' => $contrat,
        ];
    }
    
    /**
     * Téléchargement d'un fichier.
     * 
     * @throws UnAuthorizedException
     */
    public function telechargerFichierAction()
    {
        $contrat = $this->context()->mandatory()->contratFromRoute();
        $fichier = $this->context()->fichierFromRoute();
        
        if (!$this->isAllowed($contrat, ContratAssertion::PRIVILEGE_READ)) {
            throw new UnAuthorizedException("Interdit!");
        }
        
        if (!$this->isAllowed($fichier, FichierAssertion::PRIVILEGE_TELECHARGER)) {
            throw new UnAuthorizedException("Interdit!");
        }
        
        $this->uploader()->download($fichier);
    }
    
    /**
     * Suppression d'un fichier déposé.
     * 
     * @return Response
     * @throws UnAuthorizedException
     */
    public function supprimerFichierAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('home');
        }
        
        $contrat = $this->context()->mandatory()->contratFromRoute();
        $fichier = $this->context()->fichierFromRoute();
        
        if ($fichier) {
            if (!$this->isAllowed($fichier, FichierAssertion::PRIVILEGE_DELETE)) {
                throw new UnAuthorizedException("Suppression du fichier interdite!");
            }
            $contrat->removeFichier($fichier);
            $this->em()->remove($fichier);
        }
        
        $this->em()->flush();
            
        return $this->redirect()->toRoute('contrat/lister-fichier', ['contrat' => $contrat->getId()], [], true);
    }
    
    /**
     * @var ViewModel
     */
    private $view;
    
    /**
     * 
     * @return ViewModel
     */
    private function getView()
    {
        if (null === $this->view) {
            $this->view = new ViewModel();
        }
        return $this->view;
    }
    
    private $formValider;
    
    /**
     * @return ContratValidation
     */
    protected function getFormValidationContrat()
    {
        if (null === $this->formValider) {
            $this->formValider = new ContratValidation();
        }
        
        return $this->formValider;
    }
    
    private $formRetour;
    
    /**
     * @return ContratRetour
     */
    protected function getFormRetourContrat()
    {
        if (null === $this->formRetour) {
            $this->formRetour = new ContratRetour();
        }
        
        return $this->formRetour;
    }
    
    /**
     * @var Intervenant
     */
    private $intervenant;
    
    /**
     * @return Intervenant
     */
    private function getIntervenant()
    {
        if (null === $this->intervenant) {
            $this->intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }
        return $this->intervenant;
    }
    
    private $structure;
    
    private function getStructure()
    {
        if (null === $this->structure) {
            $role = $this->getContextProvider()->getSelectedIdentityRole();
            if (!$role instanceof ComposanteRole) {
                throw new LogicException("Rôle courant inattendu.");
            }
            $this->structure = $role->getStructure();
        }

        return $this->structure;
    }
    
    /**
     * @return \Application\Service\Contrat
     */
    private function getServiceContrat()
    {
        return $this->getServiceLocator()->get('ApplicationContrat');
    }
    
    /**
     * @return \Application\Service\Service
     */
    private function getServiceService()
    {
        return $this->getServiceLocator()->get('ApplicationService');
    }
    
    /**
     * @return \Application\Service\TypeVolumeHoraire
     */
    private function getServiceTypeVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire');
    }
    
    /**
     * @return \Application\Service\EtatVolumeHoraire
     */
    private function getServiceEtatVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationEtatVolumeHoraire');
    }
}
