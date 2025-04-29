<?php

namespace Referentiel\Processus;

use Application\Processus\AbstractProcessus;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\TblValidationReferentiel;
use Service\Entity\Db\TypeVolumeHoraire;
use Workflow\Entity\Db\Validation;
use Workflow\Service\TypeValidationServiceAwareTrait;
use Workflow\Service\ValidationServiceAwareTrait;


/**
 * Description of ValidationReferentielProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ValidationReferentielProcessus extends AbstractProcessus
{
    use TypeValidationServiceAwareTrait;
    use ValidationServiceAwareTrait;


    public function lister(TypeVolumeHoraire $typeVolumeHoraire, Intervenant $intervenant, Structure $structure = null)
    {
        $dql = "
        SELECT
          tvr, str, v
        FROM
          Referentiel\Entity\Db\TblValidationReferentiel tvr
          JOIN tvr.structure        str
          LEFT JOIN tvr.validation  v
        WHERE
          tvr.typeVolumeHoraire = :typeVolumeHoraire
          AND tvr.autoValidation = false
          AND tvr.intervenant = :intervenant
          " . ($structure ? 'AND tvr.structure = :structure' : '') . "
        ORDER BY
          v.id, str.libelleCourt
        ";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters(compact('typeVolumeHoraire', 'intervenant'));
        if ($structure) {
            $query->setParameter('structure', $structure);
        }
        $res = $query->execute();
        /* @var $res TblValidationReferentiel[] */

        $validations = [];
        foreach ($res as $tvr) {
            $this->getEntityManager()->detach($tvr);
            $validation        = $tvr->getValidation() ?: $this->creer($intervenant, $tvr->getStructure());
            $vid               = $this->getValidationId($validation);
            $validations[$vid] = $validation;
        }

        return $validations;
    }



    /**
     * @param TypeVolumehoraire $typeVolumeHoraire
     * @param Validation|null   $validation
     * @param boolean           $detach
     *
     * @return ServiceReferentiel[]
     */
    public function getServices(TypeVolumeHoraire $typeVolumeHoraire, Validation $validation, $detach = true)
    {
        $services = [];

        $dql = "
        SELECT
          tvr, str, s, vh, v
        FROM
          Referentiel\Entity\Db\TblValidationReferentiel tvr
          JOIN tvr.structure              str
          JOIN tvr.serviceReferentiel     s
          JOIN s.volumeHoraireReferentiel vh WITH vh = tvr.volumeHoraireReferentiel
          LEFT JOIN tvr.validation        v
        WHERE
          tvr.typeVolumeHoraire = :typeVolumeHoraire
          AND tvr.intervenant = :intervenant
          AND vh.autoValidation = false
          AND " . ($validation->getId() ? "tvr.validation = :validation" : "tvr.validation IS NULL AND tvr.structure = :structure") . "
        ";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters([
            'typeVolumeHoraire' => $typeVolumeHoraire,
            'intervenant'       => $validation->getIntervenant(),
        ]);
        if ($validation->getId()) {
            $query->setParameter('validation', $validation);
        } else {
            $query->setParameter('structure', $validation->getStructure());
        }

        $res = $query->execute();
        /* @var $res TblValidationReferentiel[] */

        foreach ($res as $tvr) {
            $service = $tvr->getServiceReferentiel();
            if ($detach) {
                $this->getEntityManager()->detach($service);
            }
            $service->setTypeVolumeHoraire($typeVolumeHoraire);
            $services[$service->getId()] = $service;
        }

        return $services;
    }



    /**
     * @param Intervenant $intervenant
     * @param Structure   $structure
     *
     * @return Validation
     */
    public function creer(Intervenant $intervenant, Structure $structure)
    {
        $typeValidation = $this->getServiceTypeValidation()->getReferentiel();

        $validation = $this->getServiceValidation()->newEntity($typeValidation)
            ->setIntervenant($intervenant)
            ->setStructure($structure);

        return $validation;
    }



    /**
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param Validation        $validation
     *
     * @return self
     */
    public function enregistrer(TypeVolumeHoraire $typeVolumeHoraire, Validation $validation)
    {
        $services = $this->getServices($typeVolumeHoraire, $validation, false);

        foreach ($services as $service) {
            foreach ($service->getVolumeHoraireReferentiel() as $vh) {
                /* @var $vh \Referentiel\Entity\Db\VolumeHoraireReferentiel */
                $validation->addVolumeHoraireReferentiel($vh);
            }
        }
        $this->getServiceValidation()->save($validation);

        return $this;
    }



    /**
     * @param Validation $validation
     *
     * @return $this
     */
    public function supprimer(Validation $validation)
    {
        $this->getServiceValidation()->delete($validation);

        return $this;
    }



    /**
     * Retourne un ID de validation. Si la validation n'existe pas alors il crée un ID composé de nv_ + ID de la structure
     *
     * @param Validation $validation
     *
     * @return int|string
     */
    public function getValidationId(Validation $validation)
    {
        if ($validation->getId()) {
            return $validation->getId();
        } else {
            return 'nv_' . $validation->getStructure()->getId();
        }
    }
}