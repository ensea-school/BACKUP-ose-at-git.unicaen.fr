<?php

namespace Intervenant\Hydrator;


use Application\Entity\Db\TypeAgrementStatut;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Application\Service\Traits\TypeAgrementServiceAwareTrait;
use Application\Service\Traits\TypeAgrementStatutServiceAwareTrait;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

/**
 *
 *
 */
class StatutIntervenantHydrator implements HydratorInterface
{

    use TypeIntervenantServiceAwareTrait;
    use TypeAgrementServiceAwareTrait;
    use TypeAgrementStatutServiceAwareTrait;
    use DossierAutreServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                         $data
     * @param \Intervenant\Entity\Db\Statut $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setDepassement($data['depassement']);
        $object->setServiceStatutaire(FloatFromString::run($data['service-statutaire']));
        if (array_key_exists('type-intervenant', $data)) {
            $object->setTypeIntervenant($this->getServiceTypeIntervenant()->get($data['type-intervenant']));
        }
        $object->setNonAutorise($data['non-autorise']);
        $object->setPeutSaisirService($data['peut-saisir-service']);
        $object->setPeutSaisirDossier($data['peut-saisir-dossier']);
        $object->setPeutSaisirReferentiel($data['peut-saisir-referentiel']);
        $object->setPeutSaisirMotifNonPaiement($data['peut-saisir-motif-non-paiement']);
        $object->setPeutAvoirContrat($data['peut-avoir-contrat']);
        $object->setPeutCloturerSaisie($data['peut-cloturer-saisie']);
        $object->setPeutSaisirServiceExt($data['peut-saisir-service-ext']);
        $object->setCode($data['code']);
        $object->setCodeRh($data['code_rh']);
        $object->setPeutChoisirDansDossier($data['peut-choisir-dans-dossier']);
        $object->setMaximumHETD(FloatFromString::run($data['maximum-HETD']));
        $object->setDepassementSDSHC($data['depassement-sdshc']);
        $object->setChargesPatronales(FloatFromString::run($data['charges-patronales']) / 100);
        $object->setDossierIdentiteComplementaire($data['dossier-identite-complementaire']);
        $object->setDossierAdresse($data['dossier-adresse']);
        $object->setDossierContact($data['dossier-contact']);
        $object->setDossierInsee($data['dossier-insee']);
        $object->setDossierIban($data['dossier-iban']);
        $object->setDossierEmployeur($data['dossier-employeur']);
        $object->setDossierEmailPerso($data['dossier-email-perso']);
        $object->setDossierTelPerso($data['dossier-tel-perso']);
        $object->setPrioritaireIndicateurs((bool)$data['prioritaire-indicateurs']);

        if (!empty($data['id'])) {
            $champsAutres = [];
            /* Gestion des champs autres */
            foreach ($data as $key => $value) {
                if (strpos($key, 'champ-autre-') !== false) {
                    $id = str_replace('champ-autre-', '', $key);
                    if ($data[$key]) {
                        $object->addChampAutre($this->getServiceDossierAutre()->get($id));
                    } else {
                        $object->removeChampAutre($this->getServiceDossierAutre()->get($id));
                    }
                }
            }
        }


        //Uniquement si le statut intervenant existe déjà en base.
        if (!empty($data['id'])) {
            //Gestion de la durée de vie des agréments par statut d'intervenant
            //On récupére les types d'agrement
            $qb             = $this->getServiceTypeAgrement()->finderByHistorique();
            $typesAgrements = $this->getServiceTypeAgrement()->getList($qb);
            //Type agrement par statut d'intervenant
            $qb = $this->getServiceTypeAgrementStatut()->finderByStatutIntervenant($object);
            $this->getServiceTypeAgrementStatut()->finderByHistorique($qb);
            $typesAgrementsStatuts      = $this->getServiceTypeAgrementStatut()->getList($qb);
            $typesAgrementsStatusByCode = [];
            foreach ($typesAgrementsStatuts as $tas) {
                $typesAgrementsStatusByCode[$tas->getType()->getCode()] = $tas;
            }
            //On boucle pour faire ensuite de l'insert, update ou delete
            foreach ($typesAgrements as $ta) {
                if (array_key_exists($ta->getCode(), $data)) {
                    if (!$data[$ta->getCode()] && array_key_exists($ta->getCode(), $typesAgrementsStatusByCode)) {
                        $tasToDelete = $typesAgrementsStatusByCode[$ta->getCode()];
                        $object->removeTypeAgrementStatut($tasToDelete);
                        $this->getServiceTypeAgrementStatut()->delete($tasToDelete);
                    } elseif ($data[$ta->getCode()] && array_key_exists($ta->getCode(), $typesAgrementsStatusByCode)) {
                        $tasToUpdate = $typesAgrementsStatusByCode[$ta->getCode()];
                        $dureeVie    = $data[$ta->getCode() . '-DUREE_VIE'];
                        $tasToUpdate->setDureeVie($dureeVie);
                        $this->getServiceTypeAgrementStatut()->save($tasToUpdate);
                    } elseif ($data[$ta->getCode()] && !array_key_exists($ta->getCode(), $typesAgrementsStatusByCode)) {
                        $dureeVie    = $data[$ta->getCode() . '-DUREE_VIE'];
                        $tasToCreate = new TypeAgrementStatut();
                        $tasToCreate->setDureeVie($dureeVie);
                        $tasToCreate->setObligatoire(1);
                        $tasToCreate->setType($ta);
                        $tasToCreate->setStatut($object);
                        $this->getServiceTypeAgrementStatut()->save($tasToCreate);
                        $object->addTypeAgrementStatut($tasToCreate);
                    }
                }
            }
        }


        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Intervenant\Entity\Db\Statut $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'                              => $object->getId(),
            'libelle'                         => $object->getLibelle(),
            'depassement'                     => $object->getDepassement(),
            'service-statutaire'              => StringFromFloat::run($object->getServiceStatutaire()),
            'peut-choisir-dans-dossier'       => $object->getPeutChoisirDansDossier(),
            'peut-saisir-dossier'             => $object->getPeutSaisirDossier(),
            'non-autorise'                    => $object->getNonAutorise(),
            'peut-saisir-service'             => $object->getPeutSaisirService(),
            'peut-saisir-referentiel'         => $object->getPeutSaisirReferentiel(),
            'peut-saisir-motif-non-paiement'  => $object->getPeutSaisirMotifNonPaiement(),
            'peut-avoir-contrat'              => $object->getPeutAvoirContrat(),
            'peut-cloturer-saisie'            => $object->getPeutCloturerSaisie(),
            'peut-saisir-service-ext'         => $object->getPeutSaisirServiceExt(),
            'type-intervenant'                => ($s = $object->getTypeIntervenant()) ? $s->getId() : null,
            'code'                            => $object->getCode(),
            'code_rh'                         => $object->getCodeRh(),
            'maximum-HETD'                    => StringFromFloat::run($object->getMaximumHETD()),
            'charges-patronales'              => StringFromFloat::run($object->getChargesPatronales() * 100),
            'depassement-sdshc'               => $object->getDepassementSDSHC(),
            'dossier-identite-complementaire' => $object->getDossierIdentiteComplementaire(),
            'dossier-adresse'                 => $object->getDossierAdresse(),
            'dossier-contact'                 => $object->getDossierContact(),
            'dossier-insee'                   => $object->getDossierInsee(),
            'dossier-iban'                    => $object->getDossierIban(),
            'dossier-employeur'               => $object->getDossierEmployeur(),
            'dossier-email-perso'             => $object->getDossierEmailPerso(),
            'dossier-tel-perso'               => $object->getDossierTelPerso(),
            'prioritaire-indicateurs'         => $object->getPrioritaireIndicateurs(),
        ];

        /*Gestion des champs autres*/
        $champsAutres = $object->getChampsAutres();
        if (!empty($champsAutres)) {
            foreach ($champsAutres as $champ) {
                $key        = 'champ-autre-' . $champ->getId();
                $data[$key] = 1;
            }
        }

        $typesAgrementsStatuts = $object->getTypeAgrementStatut();
        foreach ($typesAgrementsStatuts as $tas) {
            if (!$tas->getHistoDestruction()) {
                $data[$tas->getType()->getCode()]                = 1;
                $data[$tas->getType()->getCode() . '-DUREE_VIE'] = $tas->getDureeVie();
            }
        }


        return $data;
    }
}