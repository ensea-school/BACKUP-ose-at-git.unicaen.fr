<?php

namespace Mission\Assertion;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Candidature;
use Mission\Entity\Db\OffreEmploi;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Workflow\Entity\Db\WfEtape;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of MissionAssertion
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class OffreEmploiAssertion extends AbstractAssertion implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    use WorkflowServiceAwareTrait;

    /* ---- Routage général ---- */
    public function __invoke (array $page) // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }



    protected function assertPage (array $page)
    {
        switch ($page['route']) {
            case 'offre-emploi':
            case 'candidature':
                //Si il n'y a pas d'offre d'emploi alors il ne peut pas y avoir des candidatures
                if(!$this->canHaveCandidature())
                {
                    return false;
                }

                $role = $this->getRole();
                if (!$role) {
                    // Visible si on n'est pas connecté
                    return true;
                }
                if (!$role->getIntervenant()) {
                    //Pas visible par les gestionnaires
                    return false;
                }

                return true;
        }

        return true;
    }



    protected function assertEntity (ResourceInterface $entity = null, $privilege = null)
    {
        /** @var Role $role */
        $role = $this->getRole();

        if ($privilege && !$role->hasPrivilege($privilege)) return false;

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
                    case Privileges::MISSION_OFFRE_EMPLOI_SUPPRESSION:
                        return $this->assertOffreEmploiSupprimer($role, $entity);
                }
            break;
            case $entity instanceof Candidature:
                switch ($privilege) {
                    case Privileges::MISSION_CANDIDATURE_VALIDER:
                        return $this->assertCandidatureValider($role, $entity);
                    case Privileges::MISSION_CANDIDATURE_REFUSER:
                        return $this->assertCandidatureRefuser($role, $entity);
                }
            break;
        }

        return true;
    }



    protected function assertController ($controller, $action = null, $privilege = null)
    {
        /* @var $role Role */
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        $entity = $role->getIntervenant();
        if (!$entity) {
            $entity = $this->getMvcEvent()->getParam('intervenant');
        }
        if (!$entity) {
            $entity = $this->getMvcEvent()->getParam('mission');
        }
        if (!$entity) {
            $entity = $this->getMvcEvent()->getParam('volumeHoraireMission');
        }
        if (!$entity) {
            $entity = $this->getMvcEvent()->getParam('candidature');
        }
        if (!$entity) {
            return false;
        }

        switch ($action) {
            case 'accepter-candidature':
            case 'refuser-candidature':
                if ($entity instanceof Candidature){
                    $assert = $this->assertCandidatureValider($role, $entity);
                    return $assert;
                }
                break;
            case 'candidature':
                return $this->canHaveCandidature();
                break;
        }
        return true;
    }



    protected function assertOffreEmploiVisualisation (Role $role, OffreEmploi $offre)
    {
        return $this->asserts(
            $this->assertStructure($role, $offre->getStructure()),
        );
    }



    protected function assertStructure (Role $role, ?Structure $structure): bool
    {

        if (!$structure) {
            return true;
        }

        if (!$role->getStructure()) {
            return true;
        }

        return $structure->inStructure($role->getStructure());
    }



    protected function assertOffreEmploiEdition (Role $role, OffreEmploi $offre)
    {


        return $this->asserts([
            $this->haveRole(),
            $offre->canSaisie(),
            $this->assertOffreEmploi($role, $offre),
        ]);
    }



    protected function haveRole ()
    {
        $role = $this->getRole();

        if ($role instanceof Role) {
            return true;
        }

        return false;
    }



    protected function assertOffreEmploi (Role $role, OffreEmploi $offre)
    {
        return $this->asserts([
            $this->assertStructure($role, $offre->getStructure()),
        ]);
    }



    protected function assertOffreEmploiValidation (Role $role, OffreEmploi $offre)
    {

        return $this->asserts([
            $this->haveRole(),
            $this->assertOffreEmploi($role, $offre),
        ]);
    }



    protected function assertOffreEmploiPostuler (Role $role, OffreEmploi $offre)
    {

        //On vérifier que l'on a bien un contexte avec un intervenant
        if (!$this->haveIntervenant() || !$offre->isValide()) {
            return false;
        }


        return true;
    }



    protected function haveIntervenant ()
    {
        $role = $this->getRole();
        if ($role instanceof Role) {
            if ($role->getIntervenant() instanceof Intervenant) {
                return true;
            }
        }

        return false;
    }



    protected function assertCandidatureVisualisation (Role $role, OffreEmploi $offre)
    {
        return $this->asserts([
            $this->haveRole(),
        ]);
    }



    protected function assertOffreEmploiSupprimer (Role $role, OffreEmploi $offre)
    {
        return $this->asserts([
            !$offre->isValide(),
            $this->haveRole(),
            $this->assertOffreEmploi($role, $offre),
        ]);
    }

    protected function canHaveCandidature()
    {
        $query = 'SELECT id FROM offre_emploi WHERE histo_destruction IS NULL AND validation_id IS NOT NULL';
        $conn  = $this->getEntityManager()->getConnection();
        if (false === $conn->executeQuery($query)->fetchOne()) {
            return false;
        }
        return true;
    }



    protected function assertCandidatureValider (Role $role, Candidature $candidature)
    {
        $codeEtape = WfEtape::CANDIDATURE_VALIDATION;
        $intervenant = $candidature->getIntervenant();
        $wfEtape   = $this->getServiceWorkflow()->getEtape($codeEtape, $intervenant);
        $structureOffre = $candidature->getOffre()->getStructure();

        return $this->asserts([
            $wfEtape && $wfEtape->isAtteignable(),
            $this->assertStructure($role, $structureOffre),
        ]);
    }



    protected function assertCandidatureRefuser(Role $role, Candidature $candidature): bool
    {
        $structureOffre = $candidature->getOffre()->getStructure();
        return $this->assertStructure($role, $structureOffre);
    }

}