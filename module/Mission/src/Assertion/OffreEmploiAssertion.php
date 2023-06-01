<?php

namespace Mission\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Provider\Privilege\Privileges;
use Mission\Entity\Db\Candidature;
use Mission\Entity\Db\OffreEmploi;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenPrivilege\View\Privilege\PrivilegeViewHelper;


/**
 * Description of MissionAssertion
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class OffreEmploiAssertion extends AbstractAssertion
{

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {
        /** @var Role $role */
        $role = $this->getRole();

        //if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof OffreEmploi:
                switch ($privilege) {
                    case Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION:
                        return $this->assertOffreEmploiVisualisation($role, $entity);
                    case Privileges::MISSION_OFFRE_EMPLOI_MODIFIER:
                        return $this->assertOffreEmploiEdition($role, $entity);
                    case Privileges::MISSION_OFFRE_EMPLOI_VALIDER:
                        return $this->assertOffreEmploiValidation($role, $entity);
                    case Privileges::MISSION_OFFRE_EMPLOI_POSTULER:
                        return $this->assertOffreEmploiPostuler($role, $entity);
                    case Privileges::MISSION_CANDIDATURE_VISUALISATION:
                        return $this->assertCandidatureVisualisation($role, $entity);
                    case Privileges::MISSION_CANDIDATURE_VALIDER:
                        return $this->assertCandidatureValider($role, $entity);
                    case Privileges::MISSION_OFFRE_EMPLOI_SUPPRESSION:
                        return $this->assertOffreEmploiSupprimer($role, $entity);
                }
            break;
        }

        return true;
    }



    protected function assertOffreEmploiVisualisation(Role $role, OffreEmploi $offre)
    {
        return $this->asserts(
            $this->assertStructure($role, $offre->getStructure()),
        );
    }



    protected function assertOffreEmploiEdition(Role $role, OffreEmploi $offre)
    {
        $haveRole = $this->haveRole();

        return $this->asserts([
            $this->haveRole(),
            $offre->canSaisie(),
            $offre->haveCandidats(),
            $this->assertOffreEmploi($role, $offre),
        ]);
    }



    protected function assertOffreEmploiSupprimer(Role $role, OffreEmploi $offre)
    {
        return $this->asserts([
            $this->haveRole(),
            $this->assertOffreEmploi($role, $offre),
        ]);
    }



    protected function assertOffreEmploiPostuler(Role $role, OffreEmploi $offre)
    {
        //On vérifier que l'on a bien un contexte avec un intervenant
        if (!$this->haveIntervenant()) {
            return false;
        }
        //On vérifie qu'il reste encore des postes disponibles sur cette offre
        $nbPostes = $offre->getNombrePostes();

        $nbCandidatures = $offre->getCandidatures()->filter(
            function ($candidature) {
                /**
                 * @var Candidature $candidature
                 */
                return ($candidature->estNonHistorise() && $candidature->isValide()) ? true : false;
            }
        )->count();

        if ($nbPostes > $nbCandidatures) {
            return true;
        }

        return false;
    }



    protected function assertOffreEmploiValidation(Role $role, OffreEmploi $offre)
    {

        return $this->asserts([
            $this->haveRole(),
            $this->assertOffreEmploi($role, $offre),
        ]);
    }



    protected function assertCandidatureVisualisation(Role $role, OffreEmploi $offre)
    {
        return $this->asserts([
            $this->haveRole(),
        ]);
    }



    protected function assertCandidatureValider(Role $role, OffreEmploi $offre)
    {
        return $this->asserts([
            $this->haveRole(),
        ]);
    }



    protected function assertOffreEmploi(Role $role, OffreEmploi $offre)
    {
        return $this->asserts([
            $this->assertStructure($role, $offre->getStructure()),
        ]);
    }



    protected function assertStructure(Role $role, ?Structure $structure): bool
    {
        if (!$structure) {
            return true;
        }

        if (!$role->getStructure()) {
            return true;
        }

        $test = ($role->getStructure() == $structure);

        return $role->getStructure() == $structure;
    }



    protected function haveRole()
    {
        $role = $this->getRole();

        if ($role instanceof Role) {
            return true;
        }

        return false;
    }



    protected function haveIntervenant()
    {
        $role = $this->getRole();
        if ($role instanceof Role) {
            if ($role->getIntervenant() instanceof Intervenant) {
                return true;
            }
        }

        return false;
    }



    protected function haveCandidature()
    {

    }

}