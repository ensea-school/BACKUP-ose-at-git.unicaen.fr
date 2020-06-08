<?php

namespace Application\Hydrator;


use Application\Entity\Db\IntervenantDossier;
use Application\Entity\Db\StatutIntervenant;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\AdresseNumeroComplServiceAwareTrait;
use Application\Service\Traits\CiviliteServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DepartementServiceAwareTrait;
use Application\Service\Traits\IntervenantDossierServiceAwareTrait;
use Application\Service\Traits\PaysServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Service\Traits\VoirieServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

/**
 *
 *
 */
class IntervenantDossierHydrator implements HydratorInterface
{
    use IntervenantDossierServiceAwareTrait;
    use ContextServiceAwareTrait;
    use CiviliteServiceAwareTrait;
    use PaysServiceAwareTrait;
    use DepartementServiceAwareTrait;
    use AdresseNumeroComplServiceAwareTrait;
    use VoirieServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;

    protected $canViewBancaire;
    protected $canEditBancaire;


    /**
     *
     * @param StatutIntervenant $defaultStatut
     */
    public function __construct(StatutIntervenant $defaultStatut = null)
    {
        $this->setDefaultStatut($defaultStatut);
        $serviceAuthorize = $this->getServiceContext()->getAuthorize();
        $this->canViewBancaire = $serviceAuthorize->isAllowed(Privileges::getResourceId(Privileges::DOSSIER_BANQUE_VISUALISATION));
        $this->canViewNumeroInsee = $serviceAuthorize->isAllowed(Privileges::getResourceId(Privileges::DOSSIER_INSEE_VISUALISATION));
    }



    /**
     * Extract values from an object
     *
     * @param IntervenantDossier $object
     *
     * @return array
     */
    public function extract($object)
    {

        /* Extract fieldset dossier identite */
        $data['DossierIdentite'] = [
            'nomUsuel'             =>$object->getNomUsuel(),
            'nomPatronymique'      => $object->getNomPatronymique(),
            'prenom'               => $object->getNomUsuel(),
            'civilite'             => $object->getCivilite()->getId(),
            'dateNaissance'        => $object->getDateNaissance(),
            'paysNaissance'        => $object->getPaysNaissance()->getId(),
            'departementNaissance' => $object->getDepartementNaissance()->getId(),
            'villeNaissance'       => $object->getCommuneNaissance(),
        ];

        /* Extract fieldset dossier identite */
        $data['DossierAdresse'] = [
            'precisions'       => $object->getAdressePrecisions(),
            'lieuDit'          => $object->getAdresseLieuDit(),
            'numero'           => $object->getAdresseNumero(),
            'numeroComplement' => $object->getAdresseNumeroCompl()->getId(),
            'voirie'           => $object->getAdresseVoirie()->getId(),
            'voie'             => $object->getAdresseVoie(),
            'codePostal'       => $object->getAdresseCodePostal(),
            'ville'            => $object->getAdresseCommune(),
            'pays'             => $object->getAdressePays()->getId(),
        ];

        /* Extract fieldset dossier contact */
        $data['DossierContact'] = [
            'emailEtablissement'     => $object->getEmailPro(),
            'emailPersonnel'         => $object->getEmailPerso(),
            'telephoneProfessionnel' => $object->getTelPro(),
            'telephonePersonnel'     => $object->getTelPerso(),
        ];

        /* Extract fiedlset dossier insee */
        $data['DossierInsee'] = [
            'numeroInsee'              => ($this->canViewNumeroInsee)?$object->getNumeroInsee():$this->offendData($object->getNumeroInsee(),5),
            'numeroInseeEstProvisoire' => $object->getNumeroInseeProvisoire(),
        ];

        /* Extract fiedlset dossier bancaire */
        $data['DossierBancaire'] = [
            'ribBic'      => $object->getBIC(),
            'ribIban'     => ($this->canViewBancaire)?$object->getIBAN():$this->offendData($object->getIBAN(),5),
            'ribHorsSepa' => $object->isRibHorsSepa(),
        ];

        /* Extract statut intervenant */
        $data['statut'] = $object->getStatut()->getId();


        return $data;
    }



    /**
     * @param array  $data
     * @param object $object
     *
     * @return object
     */

    public function hydrate(array $data, $object)
    {

        //Sécurisation de l'hydratation de l'object pour ne pas mettre à jour les valeurs si on a pas le privilege

        /* @var $object IntervenantDossier */
        //Hydratation de l'indentité
        $object->setNomUsuel($data['DossierIdentite']['nomUsuel']);
        $object->setNomPatronymique($data['DossierIdentite']['nomPatronymique']);
        $object->setPrenom($data['DossierIdentite']['prenom']);
        //Civilite
        if (!empty($data['DossierIdentite']['civilite'])) {
            $civilite = $this->getServiceCivilite()->get($data['DossierIdentite']['civilite']);
            $object->setCivilite($civilite);
        }
        //Date de naissance
        if (!empty($data['DossierIdentite']['dateNaissance'])) {
            $dateNaissance = \DateTime::createFromFormat('d/m/Y', $data['DossierIdentite']['dateNaissance']);
            $object->setDateNaissance($dateNaissance);
        }
        //Pays de naissance
        if (!empty($data['DossierIdentite']['paysNaissance'])) {
            $paysNaissance = $this->getServicePays()->get($data['DossierIdentite']['paysNaissance']);
            $object->setPaysNaissance($paysNaissance);
        }
        //Departement de naissance
        if (!empty($data['DossierIdentite']['departementNaissance'])) {
            $departementNaissance = $this->getServiceDepartement()->get($data['DossierIdentite']['departementNaissance']);
            $object->setDepartementNaissance($departementNaissance);
        }
        //Hydratation de l'adresse
        $object->setCommuneNaissance($data['DossierIdentite']['villeNaissance']);
        $object->setAdressePrecisions($data['DossierAdresse']['precisions']);
        $object->setAdresseLieuDit($data['DossierAdresse']['lieuDit']);
        $object->setAdresseNumero($data['DossierAdresse']['numero']);
        /* Complement de numéro de voie */
        if (!empty($data['DossierAdresse']['numeroComplement'])) {
            $numeroComplement = $this->getServiceAdresseNumeroCompl()->get($data['DossierAdresse']['numeroComplement']);
            $object->setAdresseNumeroCompl($numeroComplement);
        }
        /* Voirie */
        if (!empty($data['DossierAdresse']['voirie'])) {
            $voirie = $this->getServiceVoirie()->get($data['DossierAdresse']['voirie']);
            $object->setAdresseVoirie($voirie);
        }

        $object->setAdresseVoie($data['DossierAdresse']['voie']);
        $object->setAdresseCodePostal($data['DossierAdresse']['codePostal']);
        $object->setAdresseCommune($data['DossierAdresse']['ville']);
        /* Pays adresse */
        if (!empty($data['DossierAdresse']['pays'])) {
            $paysAdresse = $this->getServicePays()->get($data['DossierAdresse']['pays']);
            $object->setAdressePays($paysAdresse);
        }
        //Hydratation de contact
        $object->setEmailPerso($data['DossierContact']['emailPersonnel']);
        $object->setEmailPro($data['DossierContact']['emailEtablissement']);
        $object->setTelPro($data['DossierContact']['telephoneProfessionnel']);
        $object->setTelPerso($data['DossierContact']['telephonePersonnel']);

        //Hydratation de INSEE
        if($this->canViewNumeroInsee)
        {
            $object->setNumeroInsee($data['DossierInsee']['numeroInsee']);
            $object->setNumeroInseeProvisoire($data['DossierInsee']['numeroInseeEstProvisoire']);
        }

        //Hydratation de Iban
        if($this->canViewBancaire)
        {   //$object->setIBAN($data['DossierBancaire']['ribIban']);*/
            $object->setIBAN($data['DossierBancaire']['ribIban']);
            $object->setBIC($data['DossierBancaire']['ribBic']);
            $object->setRibHorsSepa($data['DossierBancaire']['ribHorsSepa']);
        }

        //Hydratation statut
        if (!empty($data['statut'])) {
            $statut = $this->getServiceStatutIntervenant()->get($data['statut']);
            $object->setStatut($statut);
        }

        return $object;
    }



    private $defaultStatut;



    public function getDefaultStatut()
    {
        return $this->defaultStatut;
    }



    public function setDefaultStatut($defaultStatut = null)
    {
        $this->defaultStatut = $defaultStatut;

        return $this;
    }

    private function offendData($data, $length = 0)
    {
        $lengthData = strlen($data);
        $offendedData = substr($data, 0, $length);
        $offendedData = str_pad($offendedData, $lengthData, 'X');
        return $offendedData;
    }
}