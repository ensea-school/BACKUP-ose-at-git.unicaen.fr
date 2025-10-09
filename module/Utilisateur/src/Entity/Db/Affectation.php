<?php

namespace Utilisateur\Entity\Db;

use Unicaen\Framework\User\UserProfile;
use Lieu\Entity\Db\StructureAwareTrait;
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



    public function getProfile(): UserProfile
    {
        $id          = $this->getRole()->getRoleId();
        $displayName = $this->getRole()->getLibelle();

        if ($structure = $this->getStructure()) {
            $id          .= '-' . $structure->getSourceCode();
            $displayName .= ' (' . $structure->getLibelleCourt() . ')';
        }

        $profile = new UserProfile($id, $displayName);
        $profile->setContext('affectation', $this);
        $profile->setContext('role', $this->getRole());
        $profile->setContext('structure', $this->getStructure());

        return $profile;
    }

}
