<?php
namespace Application\Hydrator;


use Application\Entity\Db\IntervenantDossier;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\Traits\DepartementAwareTrait;
use Application\Service\Traits\AdresseNumeroComplServiceAwareTrait;
use Application\Service\Traits\CiviliteServiceAwareTrait;
use Application\Service\Traits\DepartementServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\IntervenantDossierServiceAwareTrait;
use Application\Service\Traits\PaysServiceAwareTrait;
use Application\Service\Traits\VoirieServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

/**
 *
 *
 */
class IntervenantDossierHydrator implements HydratorInterface
{
    use IntervenantDossierServiceAwareTrait;
    use CiviliteServiceAwareTrait;
    use PaysServiceAwareTrait;
    use DepartementServiceAwareTrait;
    use AdresseNumeroComplServiceAwareTrait;
    use VoirieServiceAwareTrait;


    /**
     *
     * @param StatutIntervenant $defaultStatut
     */
    public function __construct(StatutIntervenant $defaultStatut = null)
    {
        $this->setDefaultStatut($defaultStatut);
    }



    /**
     * Extract values from an object
     *
     * @param  IntervenantDossier $object
     *
     * @return array
     */
    public function extract($object)
    {
        /* Extract fieldset dossier identite */
        $data['DossierIdentite'] = [
            'nomUsuel'             => $object->getNomUsuel(),
            'nomPatronymique'      => $object->getNomPatronymique(),
            'prenom'               => $object->getNomUsuel(),
            'civilite'             => $object->getCivilite()->getId(),
            'dateNaissance'        => $object->getDateNaissance(),
            'paysNaissance'        => $object->getPaysNaissance()->getId(),
            'departementNaissance' => $object->getDepartementNaissance()->getId(),
            'villeNaissance'       => $object->getCommuneNaissance()
        ];

        /* Extract fieldset dossier identite */
        $data['DossierAdresse'] = [
            'precisions' => $object->getAdressePrecisions(),
            'lieuDit'    => $object->getAdresseLieuDit(),
            'numero'     => $object->getAdresseNumero(),
            'numeroComplement' => $object->getAdresseNumeroCompl(),
            'voirie'           => $object->getAdresseVoirie()->getId(),
            'voie'             => $object->getAdresseVoie(),
            'codePostal'       => $object->getAdresseCodePostal(),
            'ville'            => $object->getAdresseCommune(),
            'pays'             => $object->getAdressePays()->getId()
        ];

        /* Extract fieldset dossier contact */
        $data['DossierContact'] = [
            'emailEtablissement' => $object->getEmailPro(),
            'emailPersonnel'     => $object->getEmailPerso(),
            'telephoneProfessionnel' => $object->getTelPro(),
            'telephonePersonnel'     => $object->getTelPerso()
        ];



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

        /* @var $object IntervenantDossier*/
        $object->setNomUsuel($data['DossierIdentite']['nomUsuel']);
        $object->setNomPatronymique($data['DossierIdentite']['nomPatronymique']);
        $object->setPrenom($data['DossierIdentite']['prenom']);
        //Civilite
        if(!empty($data['DossierIdentite']['civilite']))
        {
            $civilite = $this->getServiceCivilite()->get($data['DossierIdentite']['civilite']);
            $object->setCivilite($civilite);
        }
        //Date de naissance
        if(!empty($data['DossierIdentite']['dateNaissance']))
        {
            $dateNaissance = \DateTime::createFromFormat('d/m/Y', $data['DossierIdentite']['dateNaissance']);
            $object->setDateNaissance($dateNaissance);
        }
        //Pays de naissance
        if(!empty($data['DossierIdentite']['paysNaissance']))
        {
            $paysNaissance = $this->getServicePays()->get($data['DossierIdentite']['paysNaissance']);
            $object->setPaysNaissance($paysNaissance);
        }
        //Departement de naissance
        if(!empty($data['DossierIdentite']['departementNaissance']))
        {
            $departementNaissance = $this->getServiceDepartement()->get($data['DossierIdentite']['departementNaissance']);
            $object->setDepartementNaissance($departementNaissance);
        }
        $object->setCommuneNaissance($data['DossierIdentite']['villeNaissance']);
        $object->setAdressePrecisions($data['DossierAdresse']['precisions']);
        $object->setAdresseLieuDit($data['DossierAdresse']['lieuDit']);
        $object->setAdresseNumero($data['DossierAdresse']['numero']);
        /* Complement de numéro de voie */
        if(!empty($data['DossierAdresse']['numeroComplement']))
        {
            $numeroComplement = $this->getServiceAdresseNumeroCompl()->get($data['DossierAdresse']['numeroComplement']);
            $object->setAdresseNumeroCompl($numeroComplement);
        }
        /* Voirie */
        if(!empty($data['DossierAdresse']['voirie']))
        {
            $voirie = $this->getServiceVoirie()->get($data['DossierAdresse']['voirie']);
            $object->setAdresseVoirie($voirie);
        }

        $object->setAdresseVoie($data['DossierAdresse']['voie']);
        $object->setAdresseCodePostal($data['DossierAdresse']['codePostal']);
        $object->setAdresseCommune($data['DossierAdresse']['ville']);
        /* Pays adresse */
        if(!empty($data['DossierAdresse']['pays']))
        {
            $paysAdresse = $this->getServicePays()->get($data['DossierAdresse']['pays']);
            $object->setAdressePays($paysAdresse);
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
}