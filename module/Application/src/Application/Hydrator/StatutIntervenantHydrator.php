<?php

namespace Application\Hydrator;


use Application\Entity\Db\TypeAgrementStatut;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Application\Service\Traits\TypeAgrementServiceAwareTrait;
use Application\Service\Traits\TypeAgrementStatutServiceAwareTrait;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

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
     * @param array                                    $data
     * @param \Application\Entity\Db\StatutIntervenant $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setDepassement($data['depassement']);
        $object->setPlafondReferentiel(isset($data['plafond-referentiel']) ? FloatFromString::run($data['plafond-referentiel']) : 0);
        $object->setPlafondReferentielService(isset($data['plafond-referentiel-service']) ? FloatFromString::run($data['plafond-referentiel-service']) : 9999);
        $object->setPlafondReferentielHc(isset($data['plafond-referentiel-hc']) ? FloatFromString::run($data['plafond-referentiel-hc']) : 9999);
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
        $object->setTemAtv($data['TEM-ATV']);
        $object->setTemBiatss($data['TEM-BIATSS']);
        $object->setCode($data['code']);
        $object->setPlafondHcHorsRemuFc(FloatFromString::run($data['plafond-h-h-c']));
        $object->setPlafondHcRemuFc(FloatFromString::run($data['plafond-h-c']));
        $object->setPlafondHcFiHorsEad(FloatFromString::run($data['plafond-hc-fi-hors-ead']));
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


        for ($i = 1; $i < 5; $i++) {
            if (array_key_exists('codes-corresp-' . $i, $data)) {
                $function = 'setCodesCorresp' . $i;
                $object->$function($data['codes-corresp-' . $i]);
            }
        }

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
     * @param \Application\Entity\Db\StatutIntervenant $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'                              => $object->getId(),
            'libelle'                         => $object->getLibelle(),
            'depassement'                     => $object->getDepassement(),
            'service-statutaire'              => StringFromFloat::run($object->getServiceStatutaire()),
            'plafond-referentiel'             => StringFromFloat::run($object->getPlafondReferentiel()),
            'plafond-referentiel-service'     => StringFromFloat::run($object->getPlafondReferentielService()),
            'plafond-referentiel-hc'          => StringFromFloat::run($object->getPlafondReferentielHc()),
            'peut-choisir-dans-dossier'       => $object->getPeutChoisirDansDossier(),
            'peut-saisir-dossier'             => $object->getPeutSaisirDossier(),
            'non-autorise'                    => $object->getNonAutorise(),
            'peut-saisir-service'             => $object->getPeutSaisirService(),
            'peut-saisir-referentiel'         => $object->getPeutSaisirReferentiel(),
            'peut-saisir-motif-non-paiement'  => $object->getPeutSaisirMotifNonPaiement(),
            'peut-avoir-contrat'              => $object->getPeutAvoirContrat(),
            'peut-cloturer-saisie'            => $object->getPeutCloturerSaisie(),
            'peut-saisir-service-ext'         => $object->getPeutSaisirServiceExt(),
            'TEM-ATV'                         => $object->getTemAtv(),
            'TEM-BIATSS'                      => $object->getTemBiatss(),
            'type-intervenant'                => ($s = $object->getTypeIntervenant()) ? $s->getId() : null,
            'code'                            => $object->getCode(),
            'plafond-h-h-c'                   => StringFromFloat::run($object->getPlafondHcHorsRemuFc()),
            'plafond-h-c'                     => StringFromFloat::run($object->getPlafondHcRemuFc()),
            'plafond-hc-fi-hors-ead'          => StringFromFloat::run($object->getPlafondHcFiHorsEad()),
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
        ];

        /*Gestion des champs autres*/
        $champsAutres = $object->getChampsAutres();
        if (!empty($champsAutres)) {
            foreach ($champsAutres as $champ) {
                $key        = 'champ-autre-' . $champ->getId();
                $data[$key] = 1;
            }
        }

        for ($i = 1; $i < 5; $i++) {
            $function                    = 'getCodesCorresp' . $i;
            $data['codes-corresp-' . $i] = $object->$function();
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