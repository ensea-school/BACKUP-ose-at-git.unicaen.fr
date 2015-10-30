<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Interfaces\TypeAgrementAwareInterface;
use Application\Entity\Db\Agrement;
use Application\Entity\Db\Structure;
use Application\Service\Agrement as AgrementService;
use Application\Service\TypeAgrementStatut;
use Application\Service\TypeAgrement as TypeAgrementService;
use Application\Service\TypeAgrementStatut as TypeAgrementStatutService;
use Application\Entity\Db\Traits\TypeAgrementAwareTrait;
use Doctrine\ORM\EntityManager;

/**
 * Description of AgrementFourniRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AgrementAbstractRule extends AbstractIntervenantRule implements TypeAgrementAwareInterface
{
    use TypeAgrementAwareTrait;

    /**
     *
     * @return array id => TypeAgrementStatut
     */
    protected function getTypesAgrementStatut()
    {
        $service = $this->getServiceTypeAgrementStatut();

        $qb = $service->finderByStatutIntervenant($this->getIntervenant()->getStatut());
//        if (null !== $this->getIntervenant()->getPremierRecrutement()) {
            $service->finderByPremierRecrutement($this->getIntervenant()->getPremierRecrutement(), $qb);
//        }
        $typesAgrementStatut = $service->getList($qb);

        return $typesAgrementStatut;
    }

    /**
     * Si un intervenant est spécifié, retourne les types d'agrément requis par son statut ;
     * ou sinon tous les types d'agrément existant.
     *
     * @return array id => TypeAgrement
     */
    public function getTypesAgrementAttendus()
    {
        if ($this->getIntervenant()) {
            $typesAgrementAttendus = [];
            foreach ($this->getTypesAgrementStatut() as $tas) { /* @var $tas TypeAgrementStatut */
                $type = $tas->getType();
                $typesAgrementAttendus[$type->getId()] = $type;
            }
        }
        else {
            $typesAgrementAttendus = $this->getServiceTypeAgrement()->getList();
        }

        return $typesAgrementAttendus;
    }

    /**
     *
     * @return array id => TypeAgrement
     */
    public function getTypesAgrementFournis()
    {
        $typesAgrementFournis = [];
        foreach ($this->getAgrementsFournis() as $a) { /* @var $a Agrement */
            $type = $a->getType();
            $typesAgrementFournis[$type->getId()] = $type;
        }

        return $typesAgrementFournis;
    }

    /**
     * Recherche les agréments déjà fournis.
     *
     * @param Structure|null $structure Structure concernée éventuelle
     * @return array id => Agrement
     */
    public function getAgrementsFournis(Structure $structure = null)
    {
        $qb = $this->getServiceAgrement()->finderByType($this->getTypeAgrement());
        $qb = $this->getServiceAgrement()->finderByIntervenant($this->getIntervenant(), $qb);
        $agrementsFournis = $this->getServiceAgrement()->getList($qb);

        // filtrage par structure éventuel
        if ($structure) {
            $agrements = [];
            foreach ($agrementsFournis as $agrement) { /* @var $agrement Agrement */
                if ($structure === $agrement->getStructure()) {
                    $agrements[$agrement->getId()] = $agrement;
                }
            }
            return $agrements;
        }

        return $agrementsFournis;
    }

    /**
     *
     * @return array id => Structure
     */
    public function getStructuresEnseignement()
    {
        // recherche des structures d'enseignements de l'intervenant
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default'); /* @var $em EntityManager */
        $qb = $em->getRepository('Application\Entity\Db\Structure')->createQueryBuilder("str")
                ->distinct()
                ->join("str.elementPedagogique", "ep")
                ->join("ep.service", "s")
                ->where("s.intervenant = :intervenant")
                ->setParameter('intervenant', $this->getIntervenant());
        $structuresEns = $qb->getQuery()->getResult();

        return $structuresEns;
    }

    /**
     * @return TypeAgrementService
     */
    protected function getServiceTypeAgrement()
    {
        return $this->getServiceLocator()->get('applicationTypeAgrement');
    }

    /**
     * @return TypeAgrementStatutService
     */
    protected function getServiceTypeAgrementStatut()
    {
        return $this->getServiceLocator()->get('applicationTypeAgrementStatut');
    }

    /**
     * @return AgrementService
     */
    protected function getServiceAgrement()
    {
        return $this->getServiceLocator()->get('applicationAgrement');
    }
}
