<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\RoleAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Traits\UtilisateurAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;


/**
 * Affectation
 */
class Affectation implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;
    use UtilisateurAwareTrait;
    use StructureAwareTrait;
    use RoleAwareTrait;

    /**
     * @var integer
     */
    protected $id;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}
