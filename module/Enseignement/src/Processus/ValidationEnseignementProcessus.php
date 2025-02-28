<?php

namespace Enseignement\Processus;

use Application\Processus\AbstractProcessus;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\TblValidationEnseignement;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Workflow\Entity\Db\Validation;
use Workflow\Service\TypeValidationServiceAwareTrait;
use Workflow\Service\ValidationServiceAwareTrait;


/**
 * Description of ValidationEnseignementProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ValidationEnseignementProcessus extends AbstractProcessus
{
    use TypeValidationServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;


    /**
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param Intervenant       $intervenant
     * @param Structure|null    $structure
     *
     * @return Validation[]
     */
    public function lister(TypeVolumeHoraire $typeVolumeHoraire, Intervenant $intervenant, Structure $structure = null)
    {
        $dql = "
        SELECT
          tve, str, v
        FROM
          Enseignement\Entity\Db\TblValidationEnseignement tve
          JOIN tve.structure        str
          LEFT JOIN tve.validation  v
        WHERE
          tve.typeVolumeHoraire = :typeVolumeHoraire
          AND tve.autoValidation = false
          AND tve.intervenant = :intervenant
          " . ($structure ? 'AND tve.structure = :structure' : '') . "
        ORDER BY
          v.id, str.libelleCourt
        ";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters(compact('typeVolumeHoraire', 'intervenant'));
        if ($structure) {
            $query->setParameter('structure', $structure);
        }
        $res = $query->execute();
        /* @var $res TblValidationEnseignement[] */

        $validations = [];
        foreach ($res as $tve) {
            $this->getEntityManager()->detach($tve);
            $validation        = $tve->getValidation() ?: $this->creer($intervenant, $tve->getStructure());
            $vid               = $this->getValidationId($validation);
            $validations[$vid] = $validation;
        }

        return $validations;
    }



    /**
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param Validation        $validation
     * @param bool              $detach
     *
     * @return Service[]
     */
    public function getServices(TypeVolumeHoraire $typeVolumeHoraire, Validation $validation, $detach = true)
    {
        $services = [];
        $prevu    = $this->getServiceTypeVolumeHoraire()->getPrevu();

        if ($typeVolumeHoraire != $prevu) {
            $tvf = "OR vh.typeVolumeHoraire = :prevu";
        } else {
            $tvf = '';
        }

        $dql = "
        SELECT
          tve, str, s, vh, v
        FROM
          Enseignement\Entity\Db\TblValidationEnseignement tve
          JOIN tve.structure        str
          JOIN tve.service          s
          JOIN s.volumeHoraire      vh WITH vh = tve.volumeHoraire $tvf
          LEFT JOIN tve.validation  v
        WHERE
          tve.typeVolumeHoraire = :typeVolumeHoraire
          AND vh.autoValidation = false
          AND tve.intervenant = :intervenant
          AND " . ($validation->getId() ? "tve.validation = :validation" : "tve.validation IS NULL AND tve.structure = :structure") . "
        ";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters([
            'typeVolumeHoraire' => $typeVolumeHoraire,
            'intervenant'       => $validation->getIntervenant(),
        ]);
        if ($typeVolumeHoraire != $prevu) {
            $query->setParameter('prevu', $prevu);
        }
        if ($validation->getId()) {
            $query->setParameter('validation', $validation);
        } else {
            $query->setParameter('structure', $validation->getStructure());
        }

        $res = $query->execute();
        /* @var $res TblValidationEnseignement[] */

        foreach ($res as $tve) {
            $service = $tve->getService();
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
        $typeValidation = $this->getServiceTypeValidation()->getEnseignement();

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
        /** @var Service[] $services */
        $services = $this->getServices($typeVolumeHoraire, $validation, false);

        /* Contrôle de validation */
        foreach ($services as $service) {
            foreach ($service->getVolumehoraire() as $vh) {
                /* @var $vh \Enseignement\Entity\Db\VolumeHoraire */
                if (
                    $vh->getTypeVolumeHoraire() == $typeVolumeHoraire
                    && $vh->getHeures() > 0
                    && $vh->estNonHistorise()
                    && (!$vh->isValide())
                    && $service->getElementPedagogique()
                    && !$service->getElementPedagogique()->getTypeIntervention()->contains($vh->getTypeIntervention())
                ) {
                    throw new \Exception('Des heures sont saisies sur au moins un type d\'intervention (' . $vh->getTypeIntervention() . ') non approprié. Veuillez modifier le service avant de pouvoir le valider.');
                }
            }
        }

        foreach ($services as $service) {
            foreach ($service->getVolumehoraire() as $vh) {
                if (($vh->getTypeVolumeHoraire() == $typeVolumeHoraire) && (!$vh->isValide())) {
                    /* @var $vh \Enseignement\Entity\Db\VolumeHoraire */
                    $validation->addVolumeHoraire($vh);
                }
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