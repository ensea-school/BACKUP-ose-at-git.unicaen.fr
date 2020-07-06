<?php

namespace Application\Hydrator;

use Application\Entity\Db\IntervenantDossier;
use Application\Entity\Db\StatutIntervenant;
use Application\Service\Traits\AdresseNumeroComplServiceAwareTrait;
use Application\Service\Traits\CiviliteServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DepartementServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\EmployeurServiceAwareTrait;
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
    use DossierServiceAwareTrait;
    use ContextServiceAwareTrait;
    use CiviliteServiceAwareTrait;
    use PaysServiceAwareTrait;
    use DepartementServiceAwareTrait;
    use AdresseNumeroComplServiceAwareTrait;
    use VoirieServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;
    use EmployeurServiceAwareTrait;


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
     * @param IntervenantDossier $object
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
            'civilite'             => ($object->getCivilite()) ? $object->getCivilite()->getId() : '',
            'dateNaissance'        => $object->getDateNaissance(),
            'paysNaissance'        => ($object->getPaysNaissance()) ? $object->getPaysNaissance()->getId() : '',
            'departementNaissance' => ($object->getDepartementNaissance()) ? $object->getDepartementNaissance()->getId() : '',
            'villeNaissance'       => $object->getCommuneNaissance(),
        ];

        /* Extract fieldset dossier identite */
        $data['DossierAdresse'] = [
            'precisions'       => $object->getAdressePrecisions(),
            'lieuDit'          => $object->getAdresseLieuDit(),
            'numero'           => $object->getAdresseNumero(),
            'numeroComplement' => ($object->getAdresseNumeroCompl()) ? $object->getAdresseNumeroCompl()->getId() : '',
            'voirie'           => ($object->getAdresseVoirie()) ? $object->getAdresseVoirie()->getId() : '',
            'voie'             => $object->getAdresseVoie(),
            'codePostal'       => $object->getAdresseCodePostal(),
            'ville'            => $object->getAdresseCommune(),
            'pays'             => ($object->getAdressePays()) ? $object->getAdressePays()->getId() : '',
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
            'numeroInsee'              => $object->getNumeroInsee(),
            'numeroInseeEstProvisoire' => $object->getNumeroInseeProvisoire(),
        ];

        /* Extract fiedlset dossier bancaire */
        $data['DossierBancaire'] = [
            'ribBic'      => $object->getBIC(),
            'ribIban'     => $object->getIBAN(),
            'ribHorsSepa' => $object->isRibHorsSepa(),
        ];

        /* Extract fiedlset dossier bancaire*/
        if ($object->getEmployeur()) {
            $data['DossierEmployeur'] = [
                'employeur' => [
                    'id'    => $object->getEmployeur()->getId(),
                    'label' => $object->getEmployeur()->getRaisonSociale(),
                ],
            ];
        }

        /* Extract statut intervenant */

        $data['DossierStatut']['statut'] = (!empty($object->getStatut())) ? $object->getStatut()->getId() : '';

        /* Extract Champs autres */
        /* Il faudra penser à gérer les champs de type select*/
        $data['DossierAutres'] = [
            'champ-autre-1' => $object->getAutre1(),
            'champ-autre-2' => $object->getAutre2(),
            'champ-autre-3' => $object->getAutre3(),
            'champ-autre-4' => $object->getAutre4(),
            'champ-autre-5' => $object->getAutre5(),
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

        //Sécurisation de l'hydratation de l'object pour ne pas mettre à jour les valeurs si on a pas le privilege
        /* @var $object IntervenantDossier */
        //Hydratation de l'indentité
        if (isset($data['DossierIdentite'])) {

            $object->setNomUsuel($data['DossierIdentite']['nomUsuel']);
            $object->setNomPatronymique($data['DossierIdentite']['nomPatronymique']);
            $object->setPrenom($data['DossierIdentite']['prenom']);
            //Civilite
            $civilite = (!empty($data['DossierIdentite']['civilite'])) ?
                $this->getServiceCivilite()->get($data['DossierIdentite']['civilite']) : null;
            $object->setCivilite($civilite);
            //Date de naissance
            $dateNaissance = (!empty($data['DossierIdentite']['dateNaissance'])) ?
                \DateTime::createFromFormat('d/m/Y', $data['DossierIdentite']['dateNaissance']) : null;
            $object->setDateNaissance($dateNaissance);

            //Pays de naissance
            $paysNaissance = (!empty($data['DossierIdentite']['paysNaissance'])) ?
                $this->getServicePays()->get($data['DossierIdentite']['paysNaissance']) : null;
            $object->setPaysNaissance($paysNaissance);
            //Si pays n'est pas France alors null pour département
            if (!is_null($paysNaissance) && $paysNaissance->getLibelle() == 'FRANCE') {
                $object->setDepartementNaissance(null);
            }
            //Departement de naissance
            $departementNaissance = (!empty($data['DossierIdentite']['departementNaissance'])) ?
                $this->getServiceDepartement()->get($data['DossierIdentite']['departementNaissance']) : null;
            $object->setDepartementNaissance($departementNaissance);

            $object->setCommuneNaissance($data['DossierIdentite']['villeNaissance']);
        }
        //Hydratation de l'adresse
        if (isset($data['DossierAdresse'])) {

            $object->setAdressePrecisions($data['DossierAdresse']['precisions']);
            $object->setAdresseLieuDit($data['DossierAdresse']['lieuDit']);
            $object->setAdresseNumero($data['DossierAdresse']['numero']);
            /* Complement de numéro de voie */
            $numeroComplement = (!empty($data['DossierAdresse']['numeroComplement'])) ?
                $this->getServiceAdresseNumeroCompl()->get($data['DossierAdresse']['numeroComplement']) : null;
            $object->setAdresseNumeroCompl($numeroComplement);

            /* Voirie */
            $voirie = (!empty($data['DossierAdresse']['voirie'])) ?
                $this->getServiceVoirie()->get($data['DossierAdresse']['voirie']) : null;
            $object->setAdresseVoirie($voirie);

            $object->setAdresseVoie($data['DossierAdresse']['voie']);
            $object->setAdresseCodePostal($data['DossierAdresse']['codePostal']);
            $object->setAdresseCommune($data['DossierAdresse']['ville']);
            /* Pays adresse */
            $paysAdresse = (!empty($data['DossierAdresse']['pays'])) ?
                $this->getServicePays()->get($data['DossierAdresse']['pays']) : null;
            $object->setAdressePays($paysAdresse);
        }
        //Hydratation de contact
        if (isset($data['DossierContact'])) {

            $object->setEmailPerso($data['DossierContact']['emailPersonnel']);
            $object->setEmailPro($data['DossierContact']['emailEtablissement']);
            $object->setTelPro($data['DossierContact']['telephoneProfessionnel']);
            $object->setTelPerso($data['DossierContact']['telephonePersonnel']);
        }


        //Hydratation de INSEE
        if (isset($data['DossierInsee'])) {
            $object->setNumeroInsee($data['DossierInsee']['numeroInsee']);
            $object->setNumeroInseeProvisoire($data['DossierInsee']['numeroInseeEstProvisoire']);
        } else {
            $object->setNumeroInseeProvisoire(false);
        }

        //Hydratation de Iban
        if (isset($data['DossierBancaire'])) {
            $object->setIBAN($data['DossierBancaire']['ribIban']);
            $object->setBIC($data['DossierBancaire']['ribBic']);
            $object->setRibHorsSepa($data['DossierBancaire']['ribHorsSepa']);
        }

        //Hydratation de employeur
        if (isset($data['DossierEmployeur'])) {
            $employeur = (!empty($data['DossierEmployeur']['employeur']['id'])) ?
                $this->getServiceEmployeur()->get($data['DossierEmployeur']['employeur']['id']) : null;
            $object->setEmployeur($employeur);
        }


        //Hydratation statut
        if (!empty($data['DossierStatut']['statut'])) {
            $statut = $this->getServiceStatutIntervenant()->get($data['DossierStatut']['statut']);
            $object->setStatut($statut);
        } else {
            $object->setStatut(null);
        }

        //Hydratation des champs autres
        if (isset($data['DossierAutres'])) {
            $object->setAutre1((isset($data['DossierAutres']['champ-autre-1'])) ? $data['DossierAutres']['champ-autre-1'] : '');
            $object->setAutre2((isset($data['DossierAutres']['champ-autre-2'])) ? $data['DossierAutres']['champ-autre-2'] : '');
            $object->setAutre3((isset($data['DossierAutres']['champ-autre-3'])) ? $data['DossierAutres']['champ-autre-3'] : '');
            $object->setAutre4((isset($data['DossierAutres']['champ-autre-4'])) ? $data['DossierAutres']['champ-autre-4'] : '');
            $object->setAutre5((isset($data['DossierAutres']['champ-autre-5'])) ? $data['DossierAutres']['champ-autre-5'] : '');
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
        $lengthData   = strlen($data);
        $offendedData = substr($data, 0, $length);
        $offendedData = str_pad($offendedData, $lengthData, 'X');

        return $offendedData;
    }
}