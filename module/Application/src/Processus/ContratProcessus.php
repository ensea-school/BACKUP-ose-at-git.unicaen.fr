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
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\TypeContratServiceAwareTrait;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Enseignement\Service\VolumeHoraireServiceAwareTrait;
use Enseignement\Service\Traits\VolumeHoraireServiceAwareTrait;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\Mapping\MappingException;
use Exception;
use Laminas\Mail\Message as MailMessage;
use Laminas\Mime\Message;
use Laminas\Mime\Mime;
use Laminas\Mime\Part;
use LogicException;


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
     * @param bool           $detach
     *
     * @return Service[]
     */
    public function getServices(Intervenant $intervenant, Contrat $contrat = null, Structure $structure = null, bool $detach = true): array
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
            /* @var $service Service */
            if ($detach) {
                $this->getEntityManager()->detach($service); // INDISPENSABLE si on requête N fois la même entité avec des critères différents
            }
            $services[$service->getId()] = $service;
        }

        return $services;
    }



    /**
     * @throws MappingException
     */
    public function getServicesRecaps(Contrat $contrat): array
    {
        $this->getEntityManager()->clear(Service::class);
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
    public function creer(Intervenant $intervenant, Structure $structure = null): Contrat
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
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function enregistrer(Contrat $contrat): self
    {
        if ($contrat->getId()) {
            throw new LogicException('Le contrat existe déjà. Il ne peut pas être recréé');
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
    public function supprimer(Contrat $contrat): self
    {
        if ($contrat->getValidation()) {
            throw new LogicException("Impossible de supprimer un contrat/avenant validé.");
        }

        $sVH = $this->getServiceVolumeHoraire();

        // recherche des VH liés au contrat
        $vhs = $sVH->getList($sVH->finderByContrat($contrat));

        // détachement du contrat et des VH
        $this->getORMEventListenersHistoriqueListener()->setEnabled(false);
        foreach ($vhs as $vh) {
            /* @var $vh VolumeHoraire */
            $vh->setContrat();
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
        if (($contratInitial && !$contratInitial->getValidation()) || $contrat === $contratInitial) {
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
    public function qualification(Contrat $contrat): self
    {
        if (null !== $contrat->getTypeContrat()) return $this;

        $contratInitial = $contrat->getIntervenant()->getContratInitial();
        if (($contratInitial && !$contratInitial->getValidation()) || $contrat === $contratInitial) {
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



    protected function qualificationEnAvenant(Contrat $contrat): self
    {
        $contratInitial = $contrat->getIntervenant()->getContratInitial();

        $contrat->setContrat($contratInitial);
        $contrat->setTypeContrat($this->getServiceTypeContrat()->getAvenant());
        $contrat->setNumeroAvenant($this->getServiceContrat()->getNextNumeroAvenant($contrat->getIntervenant()));

        return $this;
    }



    protected function qualificationEnContrat(Contrat $contrat): self
    {
        $contrat->setContrat();
        $contrat->setTypeContrat($this->getServiceTypeContrat()->getContrat());
        $contrat->setNumeroAvenant(0);

        return $this;
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

        $fr = $intervenant->getUniqueFormuleResultat($typeVolumeHoraire, $etatVolumeHoraire);

        return $fr->getServiceDu() + $fr->getSolde();
    }



    /**
     * @throws Exception
     */
    public function prepareMail(Contrat $contrat, string $htmlContent, string $from, string $to, string $cci = null, string $subject = null): MailMessage
    {
        $fileName = sprintf(($contrat->estUnAvenant() ? 'avenant' : 'contrat') . "_%s_%s_%s.pdf",
            $contrat->getStructure()?->getCode(),
            $contrat->getIntervenant()->getNomUsuel(),
            $contrat->getIntervenant()->getCode());

        $document = $this->getServiceContrat()->generer($contrat, false);
        $content  = $document->saveToData();

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

        $body = new Message();

        $text          = new Part($htmlContent);
        $text->type    = Mime::TYPE_HTML;
        $text->charset = 'utf-8';
        $body->addPart($text);
        $nameFrom = "Application OSE";


        //Contrat en pièce jointe
        $attachment              = new Part($content);
        $attachment->type        = 'application/pdf';
        $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
        $attachment->encoding    = Mime::ENCODING_BASE64;
        $attachment->filename    = $fileName;
        $body->addPart($attachment);

        $message     = new MailMessage();
        $messageType = 'multipart/related';
        $message->setEncoding('UTF-8')
            ->setFrom($from, $nameFrom)
            ->setSubject($subject)
            ->addTo($to)
            ->addBcc($bcc)
            ->setBody($body)
            ->getHeaders()->get('content-type')->setType($messageType);

        return $message;
    }
}