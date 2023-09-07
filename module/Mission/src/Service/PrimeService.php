<?php

namespace Mission\Service;

use Application\Acl\Role;
use Application\Entity\Db\Validation;
use Application\Provider\Privilege\Privileges;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\FichierServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Contrat\Entity\Db\Contrat;
use Mission\Assertion\SaisieAssertion;
use Mission\Entity\Db\Candidature;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\Prime;
use Mission\Entity\Db\VolumeHoraireMission;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;

/**
 * Description of PrimeService
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 *     *
 * @method Prime get($id)
 * @method Prime[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Prime newEntity()
 *
 */
class PrimeService extends AbstractEntityService
{
    use TypeVolumeHoraireServiceAwareTrait;
    use SourceServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use TypeValidationServiceAwareTrait;
    use FichierServiceAwareTrait;

    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getEntityClass (): string
    {
        return Prime::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias (): string
    {
        return 'p';
    }



    public function data (array $parameters): AxiosModel
    {
        $dql = "
        SELECT 
          p, m, str
        FROM 
          " . Prime::class . " p
          JOIN p.missions m
          JOIN m.typeMission tm
          JOIN m.structure str
        WHERE 
          m.histoDestruction IS NULL 
          " . dqlAndWhere([
                'intervenant' => 'p.intervenant',
                'prime'       => 'p',
            ], $parameters) . "
        ORDER BY
          m.dateDebut
        ";

        $query = $this->getEntityManager()->createQuery($dql)->setParameters($parameters);

        $properties = [
            'id',
            'declaration',
            'validation',
            'date_refus',
            ['missions', [['structure', ['libelleCourt']], 'libelleMission', 'dateDebut', 'dateFin', ['typeMission', ['libelle']]]],
            'histoCreation',
            'histoCreateur',
        ];

        $triggers = [];

        /*$triggers = [
            '/'                      => function (Mission $original, array $extracted) {
                $extracted['canSaisie']    = $this->getAuthorize()->isAllowed($original, Privileges::MISSION_EDITION);
                $extracted['canAddHeures'] = $this->getAuthorize()->isAllowed($original, SaisieAssertion::CAN_ADD_HEURES);
                $extracted['canValider']   = $this->getAuthorize()->isAllowed($original, Privileges::MISSION_VALIDATION);
                $extracted['canDevalider'] = $this->getAuthorize()->isAllowed($original, Privileges::MISSION_DEVALIDATION);
                $extracted['canSupprimer'] = $extracted['canSupprimer'] && $this->getAuthorize()->isAllowed($original, Privileges::MISSION_EDITION);

                return $extracted;
            },
            '/volumesHorairesPrevus' => function ($original, $extracted) {
                //$extracted['canSaisie'] &= $this->getAuthorize()->isAllowed($original, Privileges::MISSION_EDITION);
                $extracted['canValider']   = $this->getAuthorize()->isAllowed($original, Privileges::MISSION_VALIDATION);
                $extracted['canDevalider'] = $this->getAuthorize()->isAllowed($original, Privileges::MISSION_DEVALIDATION);
                $extracted['canSupprimer'] = $extracted['canSupprimer'] && $this->getAuthorize()->isAllowed($original, Privileges::MISSION_EDITION);

                return $extracted;
            },
        ];*/

        return new AxiosModel($query, $properties, $triggers);
    }



    public function validerDeclarationPrime (Contrat $contrat): Validation
    {
        $validation = $this->getServiceValidation()->newEntity($this->getServiceTypeValidation()->getDeclaration())
            ->setIntervenant($contrat->getIntervenant())
            ->setStructure($contrat->getStructure());

        $fichier = $contrat->getDeclaration()->setValidation($validation);

        $this->getServiceValidation()->save($validation);
        $this->getServiceFichier()->save($fichier);

        return $validation;
    }



    public function devaliderDeclarationPrime (Contrat $contrat): bool
    {
        $validation = $contrat->getDeclaration()->getValidation();
        $fichier    = $contrat->getDeclaration()->setValidation(null);
        $this->getServiceFichier()->save($fichier);
        $this->getEntityManager()->remove($validation);

        return true;
    }

}