<?php

namespace Mission\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Unicaen\Framework\Navigation\Page;
use Unicaen\Framework\User\UserProfile;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Candidature;
use Mission\Entity\Db\OffreEmploi;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Workflow\Entity\Db\WorkflowEtape;
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
    use ContextServiceAwareTrait;


    protected function assertPage (Page $page): bool
    {
        $page = $page->getData();
        switch ($page['route']) {
            case 'offre-emploi':
            case 'candidature':
                //Si il n'y a pas d'offre d'emploi alors il ne peut pas y avoir des candidatures
                if(!$this->canHaveCandidature())
                {
                    return false;
                }

                if ($this->authorize->isAllowedPrivilege(UserProfile::PRIVILEGE_GUEST)) {
                    // Visible si on n'est pas connecté
                    return true;
                }
                if (!$this->getServiceContext()->getIntervenant()) {
                    //Pas visible par les gestionnaires
                    return false;
                }

                return true;
        }

        return true;
    }



    protected function assertEntity (?ResourceInterface $entity = null, $privilege = null): bool
    {
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof OffreEmploi:
                switch ($privilege) {
                    case Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION:
                        return $this->assertOffreEmploiVisualisation($entity);
                    case Privileges::MISSION_OFFRE_EMPLOI_MODIFIER:
                        return $this->assertOffreEmploiEdition($entity);
                    case Privileges::MISSION_OFFRE_EMPLOI_VALIDER:
                        return $this->assertOffreEmploiValidation($entity);
                    case Privileges::MISSION_OFFRE_EMPLOI_POSTULER:
                        return $this->assertOffreEmploiPostuler($entity);
                    case Privileges::MISSION_CANDIDATURE_VISUALISATION:
                        return $this->assertCandidatureVisualisation($entity);
                    case Privileges::MISSION_OFFRE_EMPLOI_SUPPRESSION:
                        return $this->assertOffreEmploiSupprimer($entity);
                }
            break;
            case $entity instanceof Candidature:
                switch ($privilege) {
                    case Privileges::MISSION_CANDIDATURE_VALIDER:
                        return $this->assertCandidatureValider($entity);
                    case Privileges::MISSION_CANDIDATURE_REFUSER:
                        return $this->assertCandidatureRefuser($entity);
                }
            break;
        }

        return true;
    }



    protected function assertController (string $controller, ?string $action): bool
    {
        $entity = $this->getServiceContext()->getIntervenant();
        if (!$entity) {
            $entity = $this->getParam(Intervenant::class);
        }
        if (!$entity) {
            $entity = $this->getParam(Mission::class);
        }
        if (!$entity) {
            $entity = $this->getParam(VolumeHoraireMission::class);
        }
        if (!$entity) {
            $entity = $this->getParam(Candidature::class);
        }
        if (!$entity) {
            return false;
        }

        switch ($action) {
            case 'accepter-candidature':
            case 'refuser-candidature':
                if ($entity instanceof Candidature){
                    $assert = $this->assertCandidatureValider($entity);
                    return $assert;
                }
                break;
            case 'candidature':
                return $this->canHaveCandidature();
                break;
        }
        return true;
    }



    protected function assertOffreEmploiVisualisation (OffreEmploi $offre): bool
    {
        return $this->asserts(
            $this->assertStructure($offre->getStructure()),
        );
    }



    protected function assertStructure (?Structure $structure): bool
    {

        if (!$structure) {
            return true;
        }

        if (!$this->getServiceContext()->getStructure()) {
            return true;
        }

        return $structure->inStructure($this->getServiceContext()->getStructure());
    }



    protected function assertOffreEmploiEdition (OffreEmploi $offre): bool
    {


        return $this->asserts([
            $this->getServiceContext()->getAffectation(),
            $offre->canSaisie(),
            $this->assertOffreEmploi($offre),
        ]);
    }



    protected function assertOffreEmploi (OffreEmploi $offre): bool
    {
        return $this->asserts([
            $this->assertStructure($offre->getStructure()),
        ]);
    }



    protected function assertOffreEmploiValidation (OffreEmploi $offre): bool
    {

        return $this->asserts([
            $this->getServiceContext()->getAffectation(),
            $this->assertOffreEmploi($offre),
        ]);
    }



    protected function assertOffreEmploiPostuler (OffreEmploi $offre): bool
    {

        //On vérifier que l'on a bien un contexte avec un intervenant
        if (!$this->getServiceContext()->getIntervenant() || !$offre->isValide()) {
            return false;
        }


        return true;
    }



    protected function assertCandidatureVisualisation (OffreEmploi $offre): bool
    {
        return $this->asserts([
            $this->getServiceContext()->getAffectation(),
        ]);
    }



    protected function assertOffreEmploiSupprimer (OffreEmploi $offre): bool
    {
        return $this->asserts([
            !$offre->isValide(),
            $this->getServiceContext()->getAffectation(),
            $this->assertOffreEmploi($offre),
        ]);
    }

    protected function canHaveCandidature(): bool
    {
        $query = 'SELECT id FROM offre_emploi WHERE histo_destruction IS NULL AND validation_id IS NOT NULL';
        $conn  = $this->getEntityManager()->getConnection();
        if (false === $conn->executeQuery($query)->fetchOne()) {
            return false;
        }
        return true;
    }



    protected function assertCandidatureValider (Candidature $candidature): bool
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($candidature->getIntervenant(), $candidature->getOffre()->getStructure());
        $wfEtape = $feuilleDeRoute->get(WorkflowEtape::CANDIDATURE_VALIDATION);

        return $wfEtape && $wfEtape->isAllowed();
    }



    protected function assertCandidatureRefuser(Candidature $candidature): bool
    {
        $structureOffre = $candidature->getOffre()->getStructure();
        return $this->assertStructure($structureOffre);
    }

}