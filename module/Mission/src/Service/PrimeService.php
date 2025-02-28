<?php

namespace Mission\Service;


use Application\Entity\Db\Fichier;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\FichierServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Contrat\Entity\Db\TblContrat;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\Prime;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;
use Workflow\Service\TypeValidationServiceAwareTrait;
use Workflow\Service\ValidationServiceAwareTrait;

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
          LEFT JOIN p.declaration d
          LEFT JOIN p.validation v
        WHERE 
          p.histoDestruction IS NULL 
          " . dqlAndWhere([
                'intervenant' => 'p.intervenant',
                'prime'       => 'p',
            ], $parameters) . "
        ORDER BY
          p.id
        ";

        $query = $this->getEntityManager()->createQuery($dql)->setParameters($parameters);

        $properties = [
            'id',
            ['declaration', ['nom', 'id', 'histoCreation', 'histoCreateur']],
            ['validation', ['id', 'histoCreation', 'histoCreateur']],
            'dateRefus',
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



    public function creerDeclaration ($files, Prime $prime, $deleteFiles = true)
    {

        if (!$files) {
            throw new \LogicException("Aucune donnée sur les fichiers spécifiée.");
        }
        $instances = [];

        foreach ($files as $file) {
            $path          = $file['tmp_name'];
            $nomFichier    = str_replace([',', ';', ':'], '', $file['name']);
            $typeFichier   = $file['type'];
            $tailleFichier = $file['size'];

            $fichier = (new Fichier())
                ->setTypeMime($typeFichier)
                ->setNom($nomFichier)
                ->setTaille($tailleFichier)
                ->setContenu(file_get_contents($path))
                ->setValidation(null);

            $prime->setDeclaration($fichier);

            $this->getServiceFichier()->save($fichier);
            $instances[] = $fichier;

            if ($deleteFiles) {
                unlink($path);
            }
        }

        $this->getEntityManager()->flush();

        return $instances;
    }



    public function supprimerPrime (Prime $prime): bool
    {

        //On déférence la prime de toutes les missions
        /**
         * @var Mission $mission
         */
        $missions = $prime->getMissions();
        foreach ($missions as $mission) {
            $mission->setPrime(null);
            $this->entityManager->persist($mission);
        }
        $this->entityManager->flush();

        $this->delete($prime);

        return true;
    }



    public function validerDeclarationPrime (Prime $prime): bool
    {
        $validation = $this->getServiceValidation()->newEntity($this->getServiceTypeValidation()->getDeclaration())
            ->setIntervenant($prime->getIntervenant())
            ->setStructure($prime->getIntervenant()->getStructure());

        $this->getServiceValidation()->save($validation);
        $prime->setValidation($validation);
        $this->save($prime);

        return true;
    }



    public function devaliderDeclarationPrime (Prime $prime): bool
    {
        $validation = $prime->getValidation();
        $validation->historiser();
        $this->getEntityManager()->persist($validation);
        $this->getEntityManager()->flush();
        $prime->setValidation(null);
        $this->save($prime);

        return true;
    }

    public function getMissionsByIntervenant (array $parameters): ?array
    {

        $dql = "
        SELECT 
          tc,m,t
        FROM 
          " . TblContrat::class . " tc
          JOIN tc.mission m
          JOIN m.typeMission t
        WHERE 
          tc.signe = 1
          " . dqlAndWhere([
                'intervenant' => 'tc.intervenant',
            ], $parameters) . "
        ORDER BY
          m.dateDebut
        ";

        $query = $this->getEntityManager()->createQuery($dql)->setParameters($parameters);


        $contrats = $query->getResult();

        $missions = [];
        $idMissions = [];

        foreach ($contrats as $contrat) {
            $mission = $contrat->getMission();
            $now = new \DateTime();

            /**
             * @var Mission $mission
             */
            if(!in_array($mission->getId(), $idMissions) && $mission->getDateFin() < $now)
            {
                $idMissions[] = $mission->getId();
                $missions[] = $contrat->getMission();
            }
        }

        return $missions;
    }


}