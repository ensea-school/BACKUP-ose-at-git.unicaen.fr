<?php

namespace Formule\Service;


use Application\Entity\Db\Annee;
use Application\Service\AbstractService;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Doctrine\ORM\EntityRepository;
use Formule\Entity\Db\Formule;
use Formule\Entity\Db\FormuleResultatIntervenant;
use Formule\Entity\Db\FormuleTestIntervenant;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;
use Intervenant\Entity\Db\Intervenant;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenTbl\Service\BddServiceAwareTrait;

/**
 * Description of FormuleService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FormuleService extends AbstractService
{
    use ParametresServiceAwareTrait;
    use FormulatorServiceAwareTrait;
    use BddServiceAwareTrait;

    /** @var array|Formule[] */
    private array $formules = [];



    private function getRepo(): EntityRepository
    {
        return $this->getEntityManager()->getRepository(Formule::class);
    }



    private function actuCache(): void
    {
        if (empty($this->formules)) {
            $qb = $this->getRepo()->createQueryBuilder('f', 'f.id');
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



    public function get(int $id, ?Annee $annee = null): Formule
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



    public function getCurrent(?Annee $annee = null): Formule
    {
        $currentFormuleId = (int)$this->getServiceParametres()->get('formule');

        return $this->get($currentFormuleId, $annee);
    }



    private function findDelegated(Formule $formule, Annee $annee): Formule
    {
        //si la formule doit déléguer le calcul à une ancienne version, on la trouve et on la retourne
        if ($formule->getDelegationAnnee()) {
            if ($annee->getId() < $formule->getDelegationAnnee()) {
                $formule = $this->getByCode($formule->getDelegationFormule());
                $formule = $this->findDelegated($formule, $annee);
            }
        }

        return $formule;
    }



    private function makeSqlIntervenant(Formule $formule, Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): string
    {
        $sb = $this->getServiceBdd();

        $params = [
            'INTERVENANT_ID' => $intervenant->getId()
        ];
        $vIntervenant = $sb->injectKey($sb->getViewDefinition('V_FORMULE_INTERVENANT'), $params);

        $sql = $formule->getSqlIntervenant();
        $sql = str_replace('V_FORMULE_INTERVENANT', '(' . $vIntervenant . ')', $sql);

        return $sql;
    }



    private function makeSqlVolumeHoraire(Formule $formule, Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): string
    {
        $sb = $this->getServiceBdd();

        $params = [
            'INTERVENANT_ID'         => $intervenant->getId(),
            'TYPE_VOLUME_HORAIRE_ID' => $typeVolumeHoraire->getId(),
            'ETAT_VOLUME_HORAIRE_ID' => $etatVolumeHoraire->getId(),
        ];
        $vVolumeHoraire = $sb->injectKey($sb->getViewDefinition('V_FORMULE_VOLUME_HORAIRE'), $params);

        $sql = $formule->getSqlVolumeHoraire();
        $sql = str_replace('V_FORMULE_VOLUME_HORAIRE', '(' . $vVolumeHoraire . ')', $sql);

        return $sql;
    }



    public function getService(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): FormuleIntervenant
    {
        $formule = $this->getCurrent($intervenant->getAnnee());

        $sql = $this->makeSqlIntervenant($formule, $intervenant, $typeVolumeHoraire, $etatVolumeHoraire);
        $res = $this->getEntityManager()->getConnection()->fetchAssociative($sql);

        $formuleIntervenant = new FormuleIntervenant();
        $formuleIntervenant->setId($intervenant->getId());
        $formuleIntervenant->setAnnee($intervenant->getAnnee());
        $formuleIntervenant->setTypeVolumeHoraire($typeVolumeHoraire);
        $formuleIntervenant->setEtatVolumeHoraire($etatVolumeHoraire);
        $formuleIntervenant->setTypeIntervenant($intervenant->getStatut()->getTypeIntervenant());
        $formuleIntervenant->setStructureCode($res['STRUCTURE_CODE']);
        $formuleIntervenant->setHeuresServiceStatutaire((float)$res['HEURES_SERVICE_STATUTAIRE']);
        $formuleIntervenant->setHeuresServiceModifie((float)$res['HEURES_SERVICE_MODIFIE']);
        $formuleIntervenant->setDepassementServiceDuSansHC($res['DEPASSEMENT_SERVICE_DU_SANS_HC'] === '1');


        $sql = $this->makeSqlVolumeHoraire($formule, $intervenant, $typeVolumeHoraire, $etatVolumeHoraire);

        $ress = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);

        foreach ($ress as $res) {
            $fvh = new FormuleVolumeHoraire();
            $fvh->setFormuleIntervenant($formuleIntervenant);
            $formuleIntervenant->addVolumeHoraire($fvh);

            $fvh->setId((int)$res['ID']);
            $fvh->setVolumeHoraire((int)$res['VOLUME_HORAIRE_ID'] ?: null);
            $fvh->setVolumeHoraireReferentiel((int)$res['VOLUME_HORAIRE_REF_ID'] ?: null);
            $fvh->setService((int)$res['SERVICE_ID'] ?: null);
            $fvh->setServiceReferentiel((int)$res['SERVICE_REFERENTIEL_ID'] ?: null);

            $fvh->setStructureCode($res['STRUCTURE_CODE']);
            $fvh->setTypeInterventionCode($res['TYPE_INTERVENTION_CODE']);
            $fvh->setStructureUniv($res['STRUCTURE_IS_UNIV'] === '1');
            $fvh->setStructureExterieur($res['STRUCTURE_IS_EXTERIEUR'] === '1');
            $fvh->setServiceStatutaire($res['SERVICE_STATUTAIRE'] === '1');
            $fvh->setNonPayable($res['NON_PAYABLE'] === '1');

            $fvh->setTauxFi((float)$res['TAUX_FI']);
            $fvh->setTauxFa((float)$res['TAUX_FA']);
            $fvh->setTauxFc((float)$res['TAUX_FC']);
            $fvh->setTauxServiceDu((float)$res['TAUX_SERVICE_DU']);
            $fvh->setTauxServiceCompl((float)$res['TAUX_SERVICE_COMPL']);
            $fvh->setPonderationServiceDu((float)$res['PONDERATION_SERVICE_DU']);
            $fvh->setPonderationServiceCompl((float)$res['PONDERATION_SERVICE_COMPL']);
            $fvh->setHeures((float)$res['HEURES']);
        }

        return $formuleIntervenant;
    }



    public function calculer(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): FormuleIntervenant
    {
        $fi = $this->getService($intervenant, $typeVolumeHoraire, $etatVolumeHoraire);
        $formule = $this->getCurrent($intervenant->getAnnee());
        $this->getServiceFormulator()->calculer($fi, $formule);

        $resultat = $this->getResultat($intervenant, $typeVolumeHoraire, $etatVolumeHoraire);

        /* On reverse le calcul dans le résultat */

        /* On sauvegarde le résultat */

        return $fi;
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
        if (empty($formuleResultatIntervenant)){
            $formuleResultatIntervenant = new FormuleIntervenant(); // à compléter
        }

        return $formuleResultatIntervenant;
    }



    public function getTest(FormuleTestIntervenant $intervenant): FormuleIntervenant
    {
        /** @var FormuleTestIntervenant $repo */
        $repo = $this->getEntityManager()->getRepository(FormuleTestIntervenant::class);

        $dql = "
        SELECT
          fti, ftvh
        FROM
          " . FormuleTestIntervenant::class . " fti
          JOIN fti.volumesHoraires ftvh
        WHERE
          fti.id = :intervenant
        ";

        $params = [
            'intervenant' => $intervenant,
        ];

        $formuleTestIntervenant = $this->getEntityManager()->createQuery($dql)->setParameters($params)->getResult()[0];

        return $formuleTestIntervenant;
    }


}