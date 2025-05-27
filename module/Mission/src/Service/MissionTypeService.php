<?php

namespace Mission\Service;

use Application\Service\AbstractEntityService;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\CentreCoutTypeMission;
use Mission\Entity\Db\TypeMission;
use Paiement\Entity\Db\CentreCout;

/**
 * Description of MissionTypeService
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTypeService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @throws RuntimeException
     */
    public function getEntityClass(): string
    {
        return TypeMission::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'MissionType';
    }



    /**
     * @return TypeMission[]
     */
    public function getTypes(): array
    {
        $dql   = "SELECT tm FROM " . TypeMission::class . " tm";
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }



    public function getCentreCouts(): array
    {
        $dql   = "SELECT cm FROM " . CentreCout::class . " cm WHERE cm.histoDestruction IS NULL";
        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }



    public function saveCentreCoutTypeLinker($centreCoutTypeLinker)
    {
        $this->getEntityManager()->persist($centreCoutTypeLinker);
    }



    public function removeCentreCoutLinker(CentreCoutTypeMission $centreCoutTypeMission, int $anneeCourante, $softDelete = true): void
    {
        $typeMission      = $centreCoutTypeMission->getTypeMission();
        $structure        = $centreCoutTypeMission->getStructure();
        $lastCentreCoutId = $centreCoutTypeMission->getCentreCouts()->getId();

        // 1. Historiser ou supprimer l'élément courant
        if ($softDelete) {
            $centreCoutTypeMission->historiser($this->getServiceContext()->getUtilisateur());
            $this->getEntityManager()->persist($centreCoutTypeMission);
        } else {
            $this->getEntityManager()->remove($centreCoutTypeMission);
        }

        $this->getEntityManager()->flush();

        // 2. Si l’année est passée, on s’arrête là
        if ($typeMission->getAnnee()->getId() < $anneeCourante) {
            return;
        }

        // 3. Récupérer les TypeMission futurs avec le même code
        $sql = "SELECT id FROM TYPE_MISSION
            WHERE histo_destruction IS NULL
            AND code = :code
            AND annee_id > :annee
            ORDER BY annee_id ASC";

        $params = [
            'code'  => $typeMission->getCode(),
            'annee' => $typeMission->getAnnee()->getId(),
        ];

        $data = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);

        foreach ($data as $d) {
            $tm = $this->get($d['ID']);

            $filtered = $tm->getCentreCoutsTypeMission()->filter(function ($element) use ($structure) {
                return $element->getStructure()->getId() === $structure->getId() && !$element->estHistorise();
            });

            $shouldProceed = false;

            if (!$filtered->isEmpty() && $lastCentreCoutId !== null) {
                foreach ($filtered as $element) {
                    if ($element->getCentreCouts()->getId() === $lastCentreCoutId) {
                        if ($softDelete) {
                            $element->historiser($this->getServiceContext()->getUtilisateur());
                            $this->getEntityManager()->persist($element);
                        } else {
                            $this->getEntityManager()->remove($element);
                        }
                        $shouldProceed = true;
                    }
                }
                $this->getEntityManager()->flush();
            }

            if (!$shouldProceed) {
                return;
            }
        }
    }



    public function addCentreCoutTypeMission(CentreCout $centreCouts, Structure $structure, TypeMission $typeMission, int $anneeCourante): void
    {


        // 1. Historiser les éléments existants pour l'année courante
        $filtered = $typeMission->getCentreCoutsTypeMission()->filter(function ($element) use ($structure) {
            return $element->getStructure()->getId() === $structure->getId() && !$element->estHistorise();
        });

        $lastCentreCoutId = null;

        foreach ($filtered as $element) {
            $lastCentreCoutId = $element->getCentreCouts()->getId();
            $element->historiser();
            $this->getEntityManager()->persist($element);
        }

        // Si des éléments ont été historisés, on flush une seule fois
        if (!$filtered->isEmpty()) {
            $this->getEntityManager()->flush();
        }

        // 2. Créer le lien pour le typeMission courant
        $link = new CentreCoutTypeMission();
        $link->setTypeMission($typeMission);
        $link->setCentreCouts($centreCouts);
        $link->setStructure($structure);

        $this->saveCentreCoutTypeLinker($link);
        $typeMission->addCentreCoutTypeMission($link);
        $this->save($typeMission);

        // 3. Si l’année est passée, on ne traite pas les autres années
        if ($typeMission->getAnnee()->getId() < $anneeCourante) {
            return;
        }

        // 4. Traitement des autres années pour le même code
        $sql = "SELECT id FROM TYPE_MISSION
        WHERE histo_destruction IS NULL
        AND code = :code
        AND annee_id > :annee
        ORDER BY annee_id ASC";

        $params = [
            'code'  => $typeMission->getCode(),
            'annee' => $typeMission->getAnnee()->getId(),
        ];

        $data = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);

        foreach ($data as $d) {
            $tm = $this->get($d['ID']);

            $filtered = $tm->getCentreCoutsTypeMission()->filter(function ($element) use ($structure) {
                return $element->getStructure()->getId() === $structure->getId() && !$element->estHistorise();
            });

            $shouldProceed = false;

            if ($filtered->isEmpty() && $lastCentreCoutId === null) {
                $shouldProceed = true;
            } elseif (!$filtered->isEmpty() && $lastCentreCoutId !== null) {
                foreach ($filtered as $element) {
                    if ($element->getCentreCouts()->getId() === $lastCentreCoutId) {
                        $element->historiser();
                        $this->getEntityManager()->persist($element);
                        $shouldProceed = true;
                        break;
                    }
                }
                $this->getEntityManager()->flush();
            }

            if ($shouldProceed) {
                $link = new CentreCoutTypeMission();
                $link->setTypeMission($tm);
                $link->setCentreCouts($centreCouts);
                $link->setStructure($structure);

                $this->saveCentreCoutTypeLinker($link);
                $tm->addCentreCoutTypeMission($link);
                $this->save($tm);
            } else {
                return;
            }
        }
    }


}
