<?php

namespace Formule\Service;


use Administration\Service\ParametresServiceAwareTrait;
use Application\Entity\Db\Annee;
use Application\Provider\Tbl\TblProvider;
use Application\Service\AbstractService;
use Doctrine\ORM\EntityRepository;
use Formule\Entity\Db\Formule;
use Formule\Entity\Db\FormuleResultatIntervenant;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleServiceIntervenant;
use Formule\Tbl\Process\FormuleProcess;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\Statut;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;

/**
 * Description of FormuleService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FormuleService extends AbstractService
{
    use ParametresServiceAwareTrait;
    use FormulatorServiceAwareTrait;
    use TableauBordServiceAwareTrait;

    /** @var array|Formule[] */
    private array $formules = [];



    private function getRepo(): EntityRepository
    {
        return $this->getEntityManager()->getRepository(Formule::class);
    }



    private function actuCache(): void
    {
        if (empty($this->formules)) {
            $qb             = $this->getRepo()->createQueryBuilder('f', 'f.id');
            $this->formules = $qb->getQuery()->getResult();
        }
    }



    public function save(Formule $formule): self
    {
        $em = $this->getEntityManager();
        $em->persist($formule);
        $em->flush($formule);
        $this->formules[$formule->getId()] = $formule;

        return $this;
    }



    public function get(int $id, Annee|int|null $annee = null): Formule
    {
        $this->actuCache();

        if (!array_key_exists($id, $this->formules)) {
            throw new \Exception("ID $id de formule erroné : formule introuvable");
        }

        $formule = $this->formules[$id];

        if ($annee) {
            $formule = $this->findDelegated($formule, $annee);
        }

        return $formule;
    }



    public function getByCode(string $code): Formule
    {
        $this->actuCache();

        foreach ($this->formules as $formule) {
            if ($formule->getCode() == $code) {
                return $formule;
            }
        }

        throw new \Exception('Code de formule "' . $code . '" incorrect : formule introuvable');
    }



    public function getCurrent(Annee|int|null $annee = null): Formule
    {
        $currentFormuleId = (int)$this->getServiceParametres()->get('formule');

        return $this->get($currentFormuleId, $annee);
    }



    private function findDelegated(Formule $formule, Annee|int $annee): Formule
    {
        if ($annee instanceof Annee) {
            $annee = $annee->getId();
        }
        //si la formule doit déléguer le calcul à une ancienne version, on la trouve et on la retourne
        if ($formule->getDelegationAnnee()) {
            if ($annee < $formule->getDelegationAnnee()) {
                $formule = $this->getByCode($formule->getDelegationFormule());
                $formule = $this->findDelegated($formule, $annee);
            }
        }

        return $formule;
    }



    public function calculer(FormuleIntervenant $formuleIntervenant): void
    {
        $formule = $this->getCurrent($formuleIntervenant->getAnnee());
        $this->getServiceFormulator()->calculer($formuleIntervenant, $formule);
    }



    public function calculerIntervenant(Intervenant $intervenant): void
    {
        $params = [
            'INTERVENANT_ID' => $intervenant->getId(),
            'ANNEE_ID'       => $intervenant->getAnnee()->getId(),
        ];
        $this->getServiceTableauBord()->calculer(TblProvider::FORMULE, $params);
    }



    public function calculerStatut(Statut $statut): void
    {
        $params = [
            'STATUT_ID' => $statut->getId(),
            'ANNEE_ID'  => $statut->getAnnee()->getId(),
        ];
        $this->getServiceTableauBord()->calculer(TblProvider::FORMULE, $params);
    }



    public function getFormuleServiceIntervenant(int|Intervenant $intervenant, int|TypeVolumeHoraire $typeVolumeHoraire, int|EtatVolumeHoraire $etatVolumeHoraire): FormuleServiceIntervenant
    {
        $annee = null;
        if ($intervenant instanceof Intervenant) {
            $annee       = $intervenant->getAnnee()?->getId() ?? null;
            $intervenant = $intervenant->getId();
        }

        if ($typeVolumeHoraire instanceof TypeVolumeHoraire) {
            $typeVolumeHoraire = $typeVolumeHoraire->getId();
        }

        if ($etatVolumeHoraire instanceof EtatVolumeHoraire) {
            $etatVolumeHoraire = $etatVolumeHoraire->getId();
        }

        /** @var FormuleProcess $process */
        $process = $this->getServiceTableauBord()->getTableauBord(TblProvider::FORMULE)->getProcess();

        return $process->getFormuleServiceIntervenant($intervenant, $typeVolumeHoraire, $etatVolumeHoraire, $annee);
    }



    public function getResultat(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): FormuleIntervenant
    {
        /** @var EntityRepository $repo */
        $repo = $this->getEntityManager()->getRepository(FormuleResultatIntervenant::class);

        $dql = "
        SELECT
          fri, frvh
        FROM
          " . FormuleResultatIntervenant::class . " fri
          JOIN fri.volumesHoraires frvh
        WHERE
          fri.intervenant = :intervenant
          AND fri.typeVolumeHoraire = :typeVolumeHoraire
          AND fri.etatVolumeHoraire = :etatVolumeHoraire
        ";

        $params = [
            'intervenant'       => $intervenant,
            'typeVolumeHoraire' => $typeVolumeHoraire,
            'etatVolumeHoraire' => $etatVolumeHoraire,
        ];

        $formuleResultatIntervenant = $this->getEntityManager()->createQuery($dql)->setParameters($params)->getResult();
        if (empty($formuleResultatIntervenant)) {
            $formuleResultatIntervenant = new FormuleIntervenant();
            $formuleResultatIntervenant->setTypeVolumeHoraire($typeVolumeHoraire);
            $formuleResultatIntervenant->setEtatVolumeHoraire($etatVolumeHoraire);
            $formuleResultatIntervenant->setAnnee($intervenant->getAnnee());

            $formuleResultatIntervenant->setTypeIntervenant($intervenant->getStatut()->getTypeIntervenant());
            $formuleResultatIntervenant->setStructureCode($intervenant->getStructure()?->getCode());
            $formuleResultatIntervenant->setHeuresServiceStatutaire($intervenant->getStatut()->getServiceStatutaire());
            $formuleResultatIntervenant->setDepassementServiceDuSansHC($intervenant->getStatut()->getDepassementServiceDuSansHC());
        }

        return $formuleResultatIntervenant[0];
    }

}