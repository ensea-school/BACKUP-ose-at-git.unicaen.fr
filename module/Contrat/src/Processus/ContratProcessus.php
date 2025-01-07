<?php

namespace Contrat\Processus;

use Application\Entity\Db\Validation;
use Application\ORM\Event\Listeners\HistoriqueListenerAwareTrait;
use Application\Processus\AbstractProcessus;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Contrat\Entity\Db\Contrat;
use Contrat\Entity\Db\TblContrat;
use Contrat\Service\ContratServiceAwareTrait;
use Contrat\Service\TypeContratServiceAwareTrait;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\Mapping\MappingException;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Enseignement\Service\VolumeHoraireServiceAwareTrait;
use Exception;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use LogicException;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;


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
    use StructureServiceAwareTrait;
    use MailServiceAwareTrait;

    /**
     * @param Intervenant    $intervenant
     * @param Contrat|null   $contrat
     * @param Structure|null $structure
     * @param bool           $detach
     *
     * @return TblContrat[]
     */
    public function getVolumeHoraireTblContrat(string $uuid): array
    {
        $tblContrats = [];
        $query       = $this->getEntityManager()->createQuery(
            'SELECT tblc, vh
                 FROM ' . TblContrat::class . ' tblc
                 JOIN tblc.volumeHoraire vh
                 WHERE tblc.uuid = :uuid'
        );
        $query->setParameter('uuid', $uuid);

        foreach ($query->execute() as $tblContrat) {
            /** @var TblContrat $tblContrat */
            $tblContrats[$tblContrat->getId()] = $tblContrat;
        }

        return $tblContrats;
    }



    public function getVolumeHoraireRefTblContrat(string $uuid): array
    {
        $tblContrats = [];
        $query       = $this->getEntityManager()->createQuery(
            'SELECT tblc, vh
                 FROM ' . TblContrat::class . ' tblc
                 JOIN tblc.volumeHoraireRef vh
                 WHERE tblc.uuid = :uuid'
        );
        $query->setParameter('uuid', $uuid);

        foreach ($query->execute() as $tblContrat) {
            /** @var TblContrat $tblContrat */
            $tblContrats[$tblContrat->getId()] = $tblContrat;
        }

        return $tblContrats;
    }



    public function getVolumeHoraireMissionTblContrat(string $uuid): array
    {
        $tblContrats = [];
        $query       = $this->getEntityManager()->createQuery(
            'SELECT tblc, vh
                 FROM ' . TblContrat::class . ' tblc
                 JOIN tblc.volumeHoraireMission vh
                 WHERE tblc.uuid = :uuid'
        );
        $query->setParameter('uuid', $uuid);

        foreach ($query->execute() as $tblContrat) {
            /** @var TblContrat $tblContrat */
            $tblContrats[$tblContrat->getId()] = $tblContrat;
        }

        return $tblContrats;
    }



    /**
     * Création ET peuplement d'un nouveau contrat
     *
     * @param Intervenant    $intervenant
     * @param Structure|null $structure
     *
     * @return Contrat
     */
    public function creer(Intervenant $intervenant, $volumeHoraire): Contrat
    {
        $contrat = $this->getServiceContrat()->newEntity();
        /* @var $contrat Contrat */

        $contrat->setIntervenant($intervenant);

        $structure = $this->getServiceStructure()->get($volumeHoraire['structureId']);
        $contrat->setStructure($structure);

        $contrat->setTotalHetd($volumeHoraire['hetdTotal']);
        $contrat->setDebutValidite($volumeHoraire['dateDebut']);
        $contrat->setFinValidite($volumeHoraire['dateFin']);
        $contrat->setTypeContrat($this->getServiceTypeContrat()->getByCode($volumeHoraire['typeContratCode']));
        if ($volumeHoraire['contratParentId'] == NULL) {
            $contrat->setNumeroAvenant(0);
        } else {
            $this->getServiceContrat()->get($volumeHoraire['contratParentId']);
            $contratParent = $this->getServiceContrat()->get($volumeHoraire['contratParentId']);

            $contrat->setContrat($contratParent);
            $contrat->setNumeroAvenant($this->getServiceContrat()->getNextNumeroAvenant($contrat->getIntervenant()));
        }


        return $contrat;
    }



    /**
     * Enregistrement du contrat ET liaison aux volumes horaires correspondants
     *
     * @param Contrat $contrat
     *
     * @return $this
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function enregistrer(Contrat $contrat, string $uuid): self
    {
        if ($contrat->getId()) {
            throw new LogicException('Le contrat existe déjà. Il ne peut pas être recréé');
        }

        // on sauvegarde le contrat
        $this->getServiceContrat()->save($contrat);

        // on récupère les services non contractualisés et on la place les VH correspondants dans le contrat
        $tblContrats = $this->getVolumeHoraireTblContrat($uuid);
        $this->getORMEventListenersHistoriqueListener()->setEnabled(false);
        foreach ($tblContrats as $tblContrat) {
            $vh = $tblContrat->getVolumeHoraire();
            $vh->setContrat($contrat);
            $this->getEntityManager()->persist($vh);
        }

        // on récupère les services referentiel non contractualisés et on la place les VHR correspondants dans le contrat
        $tblContrats = $this->getVolumeHoraireRefTblContrat($uuid);
        $this->getORMEventListenersHistoriqueListener()->setEnabled(false);
        foreach ($tblContrats as $tblContrat) {
            $vhr = $tblContrat->getVolumeHoraireRef();
            $vhr->setContrat($contrat);
            $this->getEntityManager()->persist($vhr);
        }

//        // on récupère les heures de mission et on les places dans le contrat
        $tblContrats = $this->getVolumeHoraireMissionTblContrat($uuid);
        $this->getORMEventListenersHistoriqueListener()->setEnabled(false);
        foreach ($tblContrats as $tblContrat) {
            $vhr = $tblContrat->getVolumeHoraireRef();
            $vhr->setContrat($contrat);
            $this->getEntityManager()->persist($vhr);
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
    public function supprimer(Contrat $contrat): self
    {
        if ($contrat->getValidation()) {
            throw new LogicException("Impossible de supprimer un contrat/avenant validé.");
        }

        $sVH = $this->getServiceVolumeHoraire();

        // recherche des VH liés au contrat
        $vhs = $sVH->getList($sVH->finderByContrat($contrat));
        $vhr = $this->getEntityManager()->getRepository(VolumeHoraireReferentiel::class)->findBy(['contrat' => $contrat->getId()]);
        $vhm = $this->getEntityManager()->getRepository(VolumeHoraireMission::class)->findBy(['contrat' => $contrat->getId()]);

        // détachement du contrat et des VH
        $this->getORMEventListenersHistoriqueListener()->setEnabled(false);
        foreach ($vhs as $vh) {
            /* @var $vh VolumeHoraire */
            $vh->setContrat();
            $sVH->save($vh);
        }
        foreach ($vhr as $vh) {
            /* @var $vh VolumeHoraireReferentiel */
            $vh->setContrat();
            $this->getEntityManager()->persist($vh);
            $this->getEntityManager()->flush($vh);
        }
        foreach ($vhm as $vh) {
            /* @var $vh VolumeHoraireMission */
            $vh->setContrat();
            $this->getEntityManager()->persist($vh);
            $this->getEntityManager()->flush($vh);
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
    public function valider(Contrat $contrat): Validation
    {
        $validation = $this->getServiceValidation()->newEntity($this->getServiceTypeValidation()->getContrat())
            ->setIntervenant($contrat->getIntervenant())
            ->setStructure($contrat->getStructure());

        $this->requalification($contrat); // requalifie le contrat en avenant si nécessaire!!
        $contrat->setValidation($validation);

        if ($contrat->estUnAvenant()) {
            // On recalcule l'index, car il peut avoir changé... ? ? ?
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
    public function devalider(Contrat $contrat): self
    {
        $contrat->setValidation();
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
    public function doitEtreRequalifie(Contrat $contrat): bool
    {
        if (!$contrat->getTypeContrat()) return true; // pas de type alors oui, on qualifie!!

        $contratInitial = $contrat->getIntervenant()->getContratInitial();
        if ($contratInitial && (!$contratInitial->getValidation()) || $contratInitial === $contrat) {
            $contratInitial = null; //projet ou lui-même seulement donc on oublie
        }
        return $contrat->estUnAvenant() === !$contratInitial;
    }



    /**
     * Qualification d'un nouveau contrat en avenant ou en contrat
     *
     * @param Contrat $contrat
     *
     * @return $this
     */
    public function qualification(Contrat $contrat, Mission $mission = null): self
    {
        if (null !== $contrat->getTypeContrat()) return $this;

        $contratInitial = $contrat->getIntervenant()->getContratInitial();

        if (($contratInitial && !$contratInitial->getValidation()) || $contrat === $contratInitial) {
            $contratInitial = null; //projet ou lui-même seulement donc on oublie
        }


        if ($contratInitial) {
            if ($mission == null) {
                $this->qualificationEnAvenant($contrat);
            } else {
                $this->qualificationEnAvenant($contrat, $mission);
            }
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
    public function requalification(Contrat $contrat): self
    {
        if (!$this->doitEtreRequalifie($contrat)) return $this; // pas besoin

        if ($contrat->estUnAvenant()) {
            $this->qualificationEnContrat($contrat);
        } else {
            $this->qualificationEnAvenant($contrat);
        }

        return $this;
    }



    protected function qualificationEnAvenant(Contrat $contrat, Mission $mission = null): self
    {
        $contratInitial = $contrat->getIntervenant()->getContratInitial();

        $contrat->setContrat($contratInitial);
        $contrat->setTypeContrat($this->getServiceTypeContrat()->getAvenant());
        $contrat->setNumeroAvenant($this->getServiceContrat()->getNextNumeroAvenant($contrat->getIntervenant()));

        return $this;
    }



    protected function qualificationEnContrat(Contrat $contrat, Mission $mission = null): self
    {
        if ($mission != null) {
            $contrat->setMission($mission);
        }
        $contrat->setContrat();
        $contrat->setTypeContrat($this->getServiceTypeContrat()->getContrat());
        $contrat->setNumeroAvenant(0);

        return $this;
    }



    /**
     * @throws Exception
     */
    public function prepareMail(Contrat $contrat, string $htmlContent, string $from, string $to, string $cci = null, string $subject = null, $pieceJointe = true): Email
    {


       if (empty($subject)) {
            $subject = "Contrat " . $contrat->getIntervenant()->getCivilite() . " " . $contrat->getIntervenant()->getNomUsuel();
        }

        if (empty($to)) {
            throw new Exception("Aucun email disponible pour le destinataire / Envoi du contrat impossible");
        }
        if (empty($from)) {
            throw new Exception("Aucun email disponible pour l'expéditeur / Envoi du contrat impossible");
        }
        $bcc = [];
        if (!empty($cci)) {
            $bcc = explode(';', $cci);
        }

        $mail = new Email();
        $mail->from(new Address($from, 'Application OSE'))
            ->subject($subject)
            ->to($to)
            ->html($htmlContent);

        foreach($bcc as $address)
        {
            $mail->addBcc($address);
        }

        if ($pieceJointe) {
            //Nom du fichier
            $fileName    = sprintf(($contrat->estUnAvenant() ? 'avenant' : 'contrat') . "_%s_%s_%s.pdf",
                                   $contrat->getStructure()?->getCode(),
                                   $contrat->getIntervenant()->getNomUsuel(),
                                   $contrat->getIntervenant()->getCode());
            //Contenu du fichier
            $document                = $this->getServiceContrat()->generer($contrat, false);
            $content                 = $document->saveToData();
            $mail->attach($content, $fileName, 'application/pdf' );
        }

        return $mail;
    }




}