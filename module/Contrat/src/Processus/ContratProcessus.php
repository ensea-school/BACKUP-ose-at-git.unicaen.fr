<?php

namespace Contrat\Processus;

use Application\ORM\Event\Listeners\HistoriqueListenerAwareTrait;
use Application\Processus\AbstractProcessus;
use Application\Service\Traits\ContextServiceAwareTrait;
use Contrat\Entity\Db\Contrat;
use Contrat\Entity\Db\TblContrat;
use Contrat\Service\ContratServiceAwareTrait;
use Contrat\Service\TblContratServiceAwareTrait;
use Contrat\Service\TypeContratServiceAwareTrait;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Enseignement\Entity\Db\VolumeHoraire;
use Enseignement\Service\VolumeHoraireServiceAwareTrait;
use Exception;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use LogicException;
use Mission\Entity\Db\VolumeHoraireMission;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use Workflow\Entity\Db\Validation;
use Workflow\Service\TypeValidationServiceAwareTrait;
use Workflow\Service\ValidationServiceAwareTrait;

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
    use TblContratServiceAwareTrait;
    use IntervenantServiceAwareTrait;

    /**
     * @param Intervenant    $intervenant
     * @param Contrat|null   $contrat
     * @param Structure|null $structure
     * @param bool           $detach
     *
     * @return TblContrat[]
     */
    public function getVolumesHorairesTblContrat(string $uuid): array
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT tblc, vh
                 FROM ' . TblContrat::class . ' tblc
                 left join tblc.volumesHoraires vhs WITH vhs.uuid = tblc.uuid
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
            'SELECT tblc, vhr
                 FROM ' . TblContrat::class . ' tblc
                 JOIN tblc.volumeHoraireRef vhr
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
            'SELECT tblc, vhm
                 FROM ' . TblContrat::class . ' tblc
                left join tblc.volumesHoraires vhs WITH vhs.uuid = tblc.uuid
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
    public function creer(Contrat $contrat, TblContrat $informationContrat): Contrat
    {

        $contrat->setIntervenant($informationContrat->getIntervenant());

        $contrat->setStructure($informationContrat->getStructure());

        $dateDebut = $informationContrat->getDateDebut();

        $contrat->setDebutValidite($dateDebut);

        $dateFin = $informationContrat->getDateFin();

        $contrat->setFinValidite($dateFin);

        $contrat->setTypeContrat($informationContrat->getTypeContrat());
        $contrat->setNumeroAvenant((int)$informationContrat->getNumeroAvenant());
        $contratParent = $informationContrat->getContratParent();
        $contrat->setContrat($contratParent);

        return $contrat;
    }



    /**
     * @param Intervenant $intervenant
     *
     * @return float
     */
    public function getIntervenantTotalHetd(Intervenant $intervenant): float
    {
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getValide();

        $sql    = "SELECT total FROM formule_resultat_intervenant WHERE intervenant_id = :intervenant AND type_volume_horaire_id = :tvh AND etat_volume_horaire_id = :evh";
        $params = [
            'intervenant' => $intervenant->getId(),
            'tvh'         => $typeVolumeHoraire->getId(),
            'evh'         => $etatVolumeHoraire->getId(),
        ];
        $hetd   = (float)$this->getEntityManager()->getConnection()->fetchOne($sql, $params);

        return $hetd;
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

        $this->getServiceValidation()->save($validation);
        $contrat->setValidation($validation);
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
     * Enregistrement du contrat ET liaison aux volumes horaires correspondants
     *
     * @param Contrat $contrat
     *
     * @return $this
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function enregistrer(Contrat $contrat, ?string $uuid = null): self
    {
        if ($contrat->getId()) {
            throw new LogicException('Le contrat existe déjà. Il ne peut pas être recréé');
        }

        // on sauvegarde le contrat
        $this->getServiceContrat()->save($contrat);
        $this->getORMEventListenersHistoriqueListener()->setEnabled(false);

        $tblContrat = $this->getServiceTblContrat()->getInformationContratByUuid($uuid);

        $volumesHoraires = $tblContrat->getVolumesHoraires();
        foreach ($volumesHoraires as $tblContratVolumesHoraire) {
            $volumeHoraire = $tblContratVolumesHoraire->getVolumeHoraire()
                ?: $tblContratVolumesHoraire->getVolumeHoraireRef()
                    ?: $tblContratVolumesHoraire->getVolumeHoraireMission();

            if ($volumeHoraire !== null) {
                $volumeHoraire->setContrat($contrat);
                $this->getEntityManager()->persist($volumeHoraire);
            }
        }


        $this->getORMEventListenersHistoriqueListener()->setEnabled(true);
        $this->getEntityManager()->flush();

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
        //On recupere les informations de la tbl_contrat pour s'assurer que le contrat a les bonne données a l'enregistrement
        //Utile si un contrat a été créer depuis la création du projet par exemple


        return $contrat;
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

        foreach ($bcc as $address) {
            $mail->addBcc($address);
        }

        if ($pieceJointe) {
            //Nom du fichier
            $fileName = sprintf(
                ($contrat->estUnAvenant() ? 'avenant' : 'contrat') . "_%s_%s_%s.pdf",
                $contrat->getStructure()?->getCode(),
                $contrat->getIntervenant()->getNomUsuel(),
                $contrat->getIntervenant()->getCode()
            );
            //Contenu du fichier
            $document = $this->getServiceContrat()->generer($contrat, false);
            $content  = $document->saveToData();
            $mail->attach($content, $fileName, 'application/pdf');
        }

        return $mail;
    }


}
