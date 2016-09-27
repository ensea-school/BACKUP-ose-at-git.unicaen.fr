<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\StatutIntervenant;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\DossierAwareTrait;
use Application\Service\Traits\ServiceServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use Application\Validator\NumeroINSEEValidator;
use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Formulaire de modification du dossier d'un intervenant extérieur.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Dossier extends Form implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    use StatutIntervenantAwareTrait;
    use ContextAwareTrait;
    use DossierAwareTrait;
    use ServiceServiceAwareTrait;

    protected $dossierFieldset;

    /**
     * @var boolean
     */
    protected $readOnly;



    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $this->setHydrator(new DossierHydrator());

        $this->dossierFieldset = new DossierFieldset('dossier');
        $this->dossierFieldset
            ->setServiceLocator($this->getServiceLocator())
            ->init();

        $this->setAttribute('id', 'dossier');

        $this->add($this->dossierFieldset);

        /**
         * Csrf
         */
        $this->add(new Csrf('security'));

        /**
         * Submit
         */
        $this->add([
            'name'       => 'enregistrer',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "J'enregistre",
            ],
        ]);

    }








    public function personnaliser(Intervenant $intervenant, $lastHETD = 0)
    {
        $dossier         = $intervenant->getDossier();
        $dossierFieldset = $this->get('dossier');
        /* @var $dossierFieldset DossierFieldset */

        if ($lastHETD > 0) {
            /**
             * Si l'intervenant était un vacataire connu l'année précédente, alors
             * la question "Avez-vous exercé une activité..." est retirée puisque la réponse est forcément OUI.
             */
            $dossierFieldset->remove('premierRecrutement');
        } elseif ($this->getServiceContext()->applicationExists($this->getServiceContext()->getAnneePrecedente())) {
            /**
             * Si l'intervenant n'est pas trouvé comme vacataire l'année précédente
             * malgré que l'application était en service l'année précédente,
             * alors on ne propose pas le statut "Sans emploi et non étudiant".
             * En effet, le statut "Sans emploi et non étudiant" n'est pertinent que pour un intervenant
             * ayant été vacataire l'année précédente (et ayant perdu son activité principale).
             */
            $statutSelect = $dossierFieldset->get('statut');
            /* @var $statut \Application\Form\Intervenant\StatutSelect */
            $statutSelect->getProxy()->setStatutsToRemove([
                $this->getServiceStatutIntervenant()->getRepo()->findOneBySourceCode(StatutIntervenant::SS_EMPLOI_NON_ETUD),
            ]);
        }

        /**
         * Pas de sélection de la France par défaut si le numéro INSEE correspond à une naissance hors France.
         */
        if ($dossier->getNumeroInsee() && !$dossier->getNumeroInseeEstProvisoire()) {
            if (NumeroINSEEValidator::hasCodeDepartementEtranger($dossier->getNumeroInsee())) {
                $dossierFieldset->get('paysNaissance')->setValue(null);
            }
        }

        return $this;
    }



    /**
     * Redéfinition pour forcer le témoin "premier recrutement" en cas d'absence
     * de l'élément de formulaire.
     */
    public function setData($data)
    {
        if (!$this->dossierFieldset->has('premierRecrutement')) {
            $data->dossier['premierRecrutement'] = '0';
        }

        return parent::setData($data);
    }



    /**
     * @return boolean
     */
    public function isReadOnly()
    {
        return $this->readOnly;
    }



    /**
     * @param boolean $readOnly
     *
     * @return Dossier
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;

        $roElements = [
            'statut'                    => 'disabled',
            'nomUsuel'                  => 'readonly',
            'nomPatronymique'           => 'readonly',
            'prenom'                    => 'readonly',
            'civilite'                  => 'disabled',
            'dateNaissance'             => 'readonly',
            'paysNaissance'             => 'disabled',
            'departementNaissance'      => 'disabled',
            'villeNaissance'            => 'readonly',
            'numeroInsee'               => 'readonly',
            'numeroInseeEstProvisoire'  => 'disabled',
            'adresse'                   => 'readonly',
            //'email'                     => 'readonly',
            'emailPerso'                => 'readonly',
            'telephone'                 => 'readonly',
            'premierRecrutement'        => 'disabled',
            'rib/bic'                   => 'readonly',
            'rib/iban'                  => 'readonly',
        ];

        foreach( $roElements as $roe => $attr ){
            $roe = explode('/', $roe);
            if (2 == count($roe)){
                list($e, $se) = $roe;
            }else{
                $e = $roe[0];
                $se = null;
            }

            $element = null;
            if ($e && $this->dossierFieldset->has($e)){
                $element = $this->dossierFieldset->get($e);
                if ($se && $element->has($se)){
                    $element = $element->get($se);
                }
            }

            if ($element){
                $element->setAttribute($attr, $readOnly);
            }
        }

        return $this;
    }

}