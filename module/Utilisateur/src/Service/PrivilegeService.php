<?php

namespace Utilisateur\Service;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Doctrine\ORM\EntityRepository;
use Intervenant\Service\StatutServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenPrivilege\Entity\Db\PrivilegeInterface;
use Utilisateur\Entity\Db\Privilege;

/**
 * Description of PrivilegeService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PrivilegeService
{
    use EntityManagerAwareTrait;
    use ContextServiceAwareTrait;
    use StatutServiceAwareTrait;


    private array $privilegesCache = [];

    private array $privilegesRolesConfig = [];

    private array $noAdminPrivileges = [
        Privileges::ENSEIGNEMENT_PREVU_AUTOVALIDATION,
        Privileges::ENSEIGNEMENT_REALISE_AUTOVALIDATION,
        Privileges::REFERENTIEL_PREVU_AUTOVALIDATION,
        Privileges::REFERENTIEL_REALISE_AUTOVALIDATION,
    ];



    /**
     * @param array $privilegesRolesConfig
     */
    public function __construct(array $privilegesRolesConfig)
    {
        $this->privilegesRolesConfig = $privilegesRolesConfig;
    }



    public function getRepo(): EntityRepository
    {
        return $this->getEntityManager()->getRepository(Privilege::class);
    }



    public function get($id): ?Privilege
    {
        return $this->getRepo()->find($id);
    }



    /**
     * @return PrivilegeInterface[]
     */
    public function getList()
    {
        $qb = $this->getRepo()->createQueryBuilder('p')
                   ->addSelect('c')
                   ->join('p.categorie', 'c')
                   ->addOrderBy('c.libelle')
                   ->addOrderBy('p.ordre');

        return $qb->getQuery()->getResult();
    }

}
