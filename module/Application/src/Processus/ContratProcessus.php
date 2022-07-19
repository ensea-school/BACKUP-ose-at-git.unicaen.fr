<?php

namespace Application\Processus;

use Application\Entity\Db\Contrat;
use Service\Entity\Db\EtatVolumeHoraire;
use Application\Entity\Db\Intervenant;
use Enseignement\Entity\Db\Service;
use Application\Entity\Db\Structure;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Validation;
use Enseignement\Entity\Db\VolumeHoraire;
use Application\ORM\Event\Listeners\HistoriqueListenerAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ContratServiceAwareTrait;
use Application\Service\Traits\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\TypeContratServiceAwareTrait;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireServiceAwareTrait;


/**
 * Description of ContratProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ContratProcessus extends AbstractProcessus
{
    use ContextServiceAwareTrait;
    use ContratServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use TypeContratServiceAwareTrait;
    use TypeValidationServiceAwareTrait;
    use VolumeHoraireServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use HistoriqueListenerAwareTrait;


    /**
     * @param Intervenant    $intervenant
     * @param Contrat|null   $contrat
     * @param Structure|null $structure
     *
     * @return Service[]
     */
    public function getServices(Intervenant $intervenant, Contrat $contrat = null, Structure $structure = null, $detach = true)
    {
        $services = [];

        $fContrat    = "vh.contrat = :contrat";
        $fNonContrat = "vh.contrat IS NULL "
            . "AND tvh.code = '" . TypeVolumeHoraire::CODE_PREVU . "' "
            . "AND evh.code = '" . EtatVolumeHoraire::CODE_VALIDE . "' ";

        if ($structure) {
            $fStructure = "AND str = :structure";
        } else {
            $fStructure = '';
        }

        $dql   = "
        SELECT
          s, ep, vh, str, i, evh, tvh
        FROM
          Enseignement\Entity\Db\Service s
          JOIN s.volumeHoraire      vh
          JOIN s.elementPedagogique ep
          JOIN ep.structure         str
          JOIN s.intervenant        i
          JOIN vh.etatVolumeHoraire evh
          JOIN vh.typeVolumeHoraire tvh
        WHERE
          i = :intervenant
          AND s.histoDestruction IS NULL
          AND vh.histoDestruction IS NULL
          AND vh.motifNonPaiement IS NULL
          AND " . ($contrat ? $fContrat : $fNonContrat) . "
          $fStructure
        ";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('intervenant', $intervenant);
        if ($contrat) {
            $query->setParameter('contrat', $contrat);
        }
        if ($structure) {
            $query->setParameter('structure', $structure);
        }

        foreach ($query->execute() as $service) {
            /* @var $service \Enseignement\Entity\Db\Service */
            if ($detach) {
                $this->getEntityManager()->detach($service); // INDISPENSABLE si on requête N fois la même entité avec des critères différents
            }
            $services[$service->getId()] = $service;
        }

        return $services;
    }



    public function getServicesRecaps(Contrat $contrat)
    {
        $this->getEntityManager()->clear(\Enseignement\Entity\Db\Service::class);
        // indispensable si on requête N fois la même entité avec des critères différents

        $dql = "
        SELECT
          s, ep, vh, str, i
        FROM
          Enseignement\Entity\Db\Service s
          JOIN s.volumeHoraire      vh
          JOIN s.elementPedagogique ep
          JOIN ep.structure         str
          JOIN s.intervenant        i
          JOIN vh.contrat           c
        WHERE
          c.histoCreation <= :date
          AND i = :intervenant
          AND str = :structure
        ";
        $res = $this->getEntityManager()->createQuery($dql)->setParameters([
            "date"        => $contrat->getHistoModification(),
            "intervenant" => $contrat->getIntervenant(),
            "structure"   => $contrat->getStructure(),
        ])->getResult();

        $services = [];
        foreach ($res as $service) {
            if (0 == $service->getVolumeHoraireListe()->getHeures()) {
                continue;
            }
            $services[$service->getId()] = $service;
        }

        return $services;
    }



    /**
     * Création ET peuplement d'un nouveau contrat
     *
     * @param Intervenant    $intervenant
     * @param Structure|null $structure
     *
     * @return Contrat
     */
    public function creer(Intervenant $intervenant, Structure $structure = null)
    {
        $contrat = $this->getServiceContrat()->newEntity();
        /* @var $contrat Contrat */

        $contrat->setIntervenant($intervenant);
        $contrat->setStructure($structure);
        $contrat->setTotalHetd($this->getIntervenantTotalHetd($intervenant));
        $this->qualification($contrat); // init contrat/avenant

        return $contrat;
    }



    /**
     * Enregistrement du contrat ET liaison aux volumes horaires correspondants
     *
     * @param Contrat $contrat
     *
     * @return $this
     */
    public function enregistrer(Contrat $contrat)
    {
        if ($contrat->getId()) {
            throw new \LogicException('Le contrat existe déjà. Il ne peut pas être recréé');
        }

        // on sauvegarde le contrat
        $this->getServiceContrat()->save($contrat);

        // on récupère les services non contractualisés et on la place les VH correspondants dans le contrat
        $services = $this->getServices($contrat->getIntervenant(), null, $contrat->getStructure(), false);
        $this->getORMEventListenersHistoriqueListener()->setEnabled(false);
        foreach ($services as $service) {
            foreach ($service->getVolumeHoraire() as $vh) {
                /* @var $vh VolumeHoraire */
                $vh->setContrat($contrat);
                $this->getEntityManager()->persist($vh);
            }
        }
        $this->getORMEventListenersHistoriqueListener()->setEnabled(true);
        $this->getEntityManager()->flush();

        return $this;
    }



    /**
     * Suppression (historisation) d'un projet de contrat/avenant.
     *
     * @param Contrat $contrat
     *
     * @return self
     */
    public function supprimer(Contrat $contrat)
    {
        if ($contrat->getValidation()) {
            throw new \LogicException("Impossible de supprimer un contrat/avenant validé.");
        }

        $sVH = $this->getServiceVolumeHoraire();

        // recherche des VH liés au contrat
        $vhs = $sVH->getList($sVH->finderByContrat($contrat));

        // détachement du contrat et des VH
        $this->getORMEventListenersHistoriqueListener()->setEnabled(false);
        foreach ($vhs as $vh) {
            /* @var $vh \Enseignement\Entity\Db\VolumeHoraire */
            $vh->setContrat(null);
            $sVH->save($vh);
        }
        $this->getORMEventListenersHistoriqueListener()->setEnabled(true);
        $this->getServiceContrat()->delete($contrat);

        return $this;
    }



    /**
     *
     * @param Contrat $contrat
     *
     * @return Validation
     */
    public function valider(Contrat $contrat)
    {
        $validation = $this->getServiceValidation()->newEntity($this->getServiceTypeValidation()->getContrat())
            ->setIntervenant($contrat->getIntervenant())
            ->setStructure($contrat->getStructure());

        $this->requalification($contrat); // requalifie le contrat en avenant si nécessaire!!
        $contrat->setValidation($validation);

        if ($contrat->estUnAvenant()) {
            // on recalcule l'index car il peut avoir changé... ? ? ?
            $contrat->setNumeroAvenant($this->getServiceContrat()->getNextNumeroAvenant($contrat->getIntervenant()));
        }

        $this->getServiceValidation()->save($validation);
        $this->getServiceContrat()->save($contrat);

        return $validation;
    }



    /**
     *
     * @param Contrat $contrat
     *
     * @return self
     */
    public function devalider(Contrat $contrat)
    {
        $contrat->setValidation(null);
        $this->getServiceContrat()->save($contrat);

        return $this;
    }



    /**
     * Détermine si le contrat doit être requalifié ou non
     *
     * @param Contrat $contrat
     *
     * @return bool
     */
    public function doitEtreRequalifie(Contrat $contrat)
    {
        if (!$contrat->getTypeContrat()) return true; // pas de type alors oui, on qualifie!!

        $contratInitial = $contrat->getIntervenant()->getContratInitial();
        if (($contratInitial && !$contratInitial->getValidation()) || $contrat == $contratInitial) {
            $contratInitial = null; //projet ou lui-même seulement donc on oublie
        }

        $result = (bool)$contrat->estUnAvenant() === !(bool)$contratInitial;

        return $result;
    }



    /**
     * Qualification d'un nouveau contrat en avenant ou en contrat
     *
     * @param Contrat $contrat
     *
     * @return $this
     */
    public function qualification(Contrat $contrat)
    {
        if (null !== $contrat->getTypeContrat()) return $this;

        $contratInitial = $contrat->getIntervenant()->getContratInitial();
        if (($contratInitial && !$contratInitial->getValidation()) || $contrat == $contratInitial) {
            $contratInitial = null; //projet ou lui-même seulement donc on oublie
        }

        if ($contratInitial) {
            $this->qualificationEnAvenant($contrat);
        } else {
            $this->qualificationEnContrat($contrat);
        }

        return $this;
    }



    /**
     * Requalification d'un contrat en avenant ou d'un avenant en contrat
     *
     * @param Contrat $contrat
     *
     * @return $this
     */
    public function requalification(Contrat $contrat)
    {
        if (!$this->doitEtreRequalifie($contrat)) return $this; // pas besoin

        if ($contrat->estUnAvenant()) {
            $this->qualificationEnContrat($contrat);
        } else {
            $this->qualificationEnAvenant($contrat);
        }

        return $this;
    }



    protected function qualificationEnAvenant(Contrat $contrat)
    {
        $contratInitial = $contrat->getIntervenant()->getContratInitial();

        $contrat->setContrat($contratInitial);
        $contrat->setTypeContrat($this->getServiceTypeContrat()->getAvenant());
        $contrat->setNumeroAvenant($this->getServiceContrat()->getNextNumeroAvenant($contrat->getIntervenant()));

        return $this;
    }



    protected function qualificationEnContrat(Contrat $contrat)
    {
        $contrat->setContrat(null);
        $contrat->setTypeContrat($this->getServiceTypeContrat()->getContrat());
        $contrat->setNumeroAvenant(0);

        return $this;
    }



    /**
     * @return float
     */
    public function getIntervenantTotalHetd(Intervenant $intervenant)
    {
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getValide();

        $fr = $intervenant->getUniqueFormuleResultat($typeVolumeHoraire, $etatVolumeHoraire);

        return $fr->getServiceDu() + $fr->getSolde();
    }

}