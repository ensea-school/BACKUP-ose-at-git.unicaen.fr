<?php

namespace Intervenant\Service;

use Application\Entity\Db\Annee;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Framework\Application\Application;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\Statut;
use Intervenant\Entity\Db\TypeIntervenant;
use Laminas\Hydrator\ClassMethodsHydrator;
use Paiement\Service\MiseEnPaiementIntervenantStructureServiceAwareTrait;
use Paiement\Service\MiseEnPaiementServiceAwareTrait;
use RuntimeException;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of Intervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Intervenant get($id)
 * @method Intervenant[] getList(?QueryBuilder $qb = null, $alias = null)
 */
class IntervenantService extends AbstractEntityService
{
    use StatutServiceAwareTrait;
    use MiseEnPaiementServiceAwareTrait;
    use MiseEnPaiementIntervenantStructureServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use SourceServiceAwareTrait;
    use AnneeServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass ()
    {
        return Intervenant::class;
    }



    /**
     * Retourne l'intervenant en fonction des paramètres de route transmis :
     * - soit code:<i>CODE</i>
     * - soit <i>ID</i>
     *
     * @param string $routeParam
     *
     * @return Intervenant|null
     */
    public function getByRouteParam (string $routeParam): ?Intervenant
    {
        $code        = null;
        $anneeId     = $this->getServiceContext()->getAnnee()->getId();
        $statutId    = null;
        $structureId = $this->getServiceContext()->getStructure();
        if ($structureId) $structureId = $structureId->getId();

        if (0 === strpos($routeParam, 'code:')) {
            // liste des intervenants filtrée par code
            $code  = substr($routeParam, 5);
            $bones = $this->getBones(['CODE' => $code, 'ANNEE_ID' => $anneeId]);
        } else {
            // liste ds intervenants par ID (1 seul)
            $bones = $this->getBones(['ID' => (int)$routeParam]);
            if (isset($bones[0]['ANNEE_ID']) && $bones[0]['ANNEE_ID'] != $anneeId) {
                $code     = $bones[0]['CODE'];
                $statutId = (int)$bones[0]['STATUT_ID'];
                $nbones   = $this->getBones(['CODE' => $code, 'ANNEE_ID' => $anneeId]);
                if (!empty($nbones)) {
                    $bones = $nbones;
                }
            }
        }

        return $this->bestIntervenantByBones($bones, $code, $anneeId, $statutId, $structureId);
    }



    /**
     * Retourne les identifiants des données concernés
     *
     * @param string|string[]|null $sourceCode
     * @param integer|null         $anneeId
     *
     * @return integer[]|null
     */
    protected function getId ($column, $value, $anneeId = null)
    {
        if (empty($sourceCode)) return null;

        $sql = 'SELECT ID FROM INTERVENANT WHERE ' . $column . ' IN (:value)';
        if ($anneeId) {
            $sql .= ' AND ANNEE_ID = ' . (string)(int)$anneeId;
        }
        $stmt = $this->getEntityManager()->getConnection()->executeQuery(
            $sql,
            ['value' => (array)$value],
            ['value' => \Doctrine\DBAL\Connection::PARAM_INT_ARRAY]
        );
        if ($r = $stmt->fetch()) {
            return (int)$r['ID'];
        } else {
            return null;
        }
    }



    private function getBones (array $params): array
    {
        $psql = '';
        foreach ($params as $param => $val) {
            $psql .= " AND i.$param = :$param";
        }
        $isql = "
          SELECT 
            i.ID, i.CODE, i.ANNEE_ID, i.STATUT_ID, i.STRUCTURE_ID, i.HISTO_DESTRUCTION,
            si.libelle STATUT_LIBELLE, 
            rownum POIDS
          FROM 
            intervenant i
            JOIN statut si ON si.id = i.statut_id 
          WHERE 
            1=1
            $psql
          ORDER BY
            si.ORDRE
        ";

        return $this->getEntityManager()->getConnection()->fetchAllAssociative($isql, $params);
    }



    private function bestIntervenantByBones (array $bones, ?string $code, ?int $anneeId, ?int $statutId, ?int $structureId): ?Intervenant
    {
        $count = count($bones);
        if (0 == $count) {
            // rien si aucun intervenant ne correspond
            return null;
        }
        if ($count == 1) {
            // si on l'a trouvé, alors on retourne l'entité
            return $this->get($bones[0]['ID']);
        }

        $iDef = $this->getIntervenantIdParDefaut($code, $anneeId);

        $poidMin = 999999999999999;
        // on calcule le poids : plus c'est gros moins c'est interessant.
        foreach ($bones as $i => $data) {
            if ($data['HISTO_DESTRUCTION']) $bones[$i]['POIDS'] += 1000000;
            if ($statutId && $data['STATUT_ID'] && $data['STATUT_ID'] != $statutId) $bones[$i]['POIDS'] += 100000;
            if ($iDef && $data['ID'] != $iDef) $bones[$i]['POIDS'] += 10000;
            if ($structureId && $data['STRUCTURE_ID'] && $data['STRUCTURE_ID'] != $structureId) $bones[$i]['POIDS'] += 1000;

            if ($bones[$i]['POIDS'] < $poidMin) $poidMin = $bones[$i]['POIDS'];
        }

        foreach ($bones as $i => $data) {
            if ($data['POIDS'] == $poidMin) {
                // on retourne le plus petit poids
                return $this->get($data['ID']);
            }
        }

        // Sinon rien, mais c'est improbable!
        return null;
    }



    private function getIntervenantIdParDefaut (string $code, int $anneeId): ?int
    {
        if (!$code || !$anneeId) return null;

        $sql  = "SELECT intervenant_id FROM intervenant_par_defaut WHERE intervenant_code = :code AND annee_id = :annee";
        $iDef = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, ['code' => $code, 'annee' => $anneeId]);
        if (isset($iDef[0]['INTERVENANT_ID'])) {
            return (int)$iDef[0]['INTERVENANT_ID'];
        }

        return null;
    }



    /**
     * Retourne l'intervenant de l'année précédente
     *
     * @param Intervenant $intervenant
     * @param int         $anneeDiff
     *
     * @return Intervenant|null
     */
    public function getPrecedent (Intervenant $intervenant, int $anneeDiff = -1): ?Intervenant
    {
        $code        = $intervenant->getCode();
        $anneeId     = $this->getServiceContext()->getAnnee()->getId() + $anneeDiff;
        $statutId    = $intervenant->getStatut() ? $intervenant->getStatut()->getId() : null;
        $structureId = $intervenant->getStructure() ? $intervenant->getStructure()->getId() : null;

        $bones = $this->getBones(['CODE' => $code, 'ANNEE_ID' => $anneeId]);

        return $this->bestIntervenantByBones($bones, $code, $anneeId, $statutId, $structureId);
    }



    public function getByCodeRh (string $codeRh): ?Intervenant
    {
        $anneeId     = $this->getServiceContext()->getAnnee()->getId();
        $statutId    = null;
        $structureId = $this->getServiceContext()->getStructure();
        if ($structureId) $structureId = $structureId->getId();

        $bones = $this->getBones(['CODE_RH' => $codeRh, 'ANNEE_ID' => $anneeId]);

        return $this->bestIntervenantByBones($bones, $codeRh, $anneeId, null, null);
    }



    /**
     *
     * @param string $sourceCode
     * @param Annee  $annee
     *
     * @return Intervenant|null
     */
    public function getByUtilisateurCode (string $utilisateurCode): ?Intervenant
    {
        $anneeId     = $this->getServiceContext()->getAnnee()->getId();
        $statutId    = null;
        $structureId = $this->getServiceContext()->getStructure();
        if ($structureId) $structureId = $structureId->getId();

        $bones = $this->getBones(['UTILISATEUR_CODE' => $utilisateurCode, 'ANNEE_ID' => $anneeId]);
        $code  = null;
        foreach ($bones as $bone) {
            if (!$code) $code = $bone['CODE'];
            if ($code != $bone['CODE']) {
                throw new \Exception('Intervenants différents retournés');
            }
        }

        return $this->bestIntervenantByBones($bones, $code, $anneeId, $statutId, $structureId);
    }



    public function isImportable (Intervenant $intervenant): bool
    {
        $connection = $this->getEntityManager()->getConnection();

        $sqlEnabled = "SELECT sync_enabled FROM import_tables WHERE table_name = 'INTERVENANT'";
        $res        = $connection->fetchAssociative($sqlEnabled);
        if (false === $res || '0' == $res['SYNC_ENABLED']) return false;

        $sql = "SELECT code FROM src_intervenant WHERE code = :code AND annee_id = :annee";
        $res = $connection->fetchAssociative($sql, ['code' => $intervenant->getCode(), 'annee' => $intervenant->getAnnee()->getId()]);

        return ($res !== false) && isset($res['CODE']) && ($intervenant->getCode() == $res['CODE']);
    }



    /**
     * Permet de définit une fiche intervenant comme celle étant à consulter par défaut
     *
     * @param Intervenant $intervenant
     * @param bool        $definir
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function definirParDefaut (Intervenant $intervenant, bool $definir = true)
    {
        if ($definir) {
            $idParDefaut = $this->getIntervenantIdParDefaut($intervenant->getCode(), $intervenant->getAnnee()->getId());
            if ($idParDefaut) {
                $sql = "DELETE FROM intervenant_par_defaut WHERE intervenant_id = :id";
                $this->getEntityManager()->getConnection()->executeUpdate($sql, ['id' => $idParDefaut]);
            }
            $sql = "INSERT INTO intervenant_par_defaut (id, annee_id, intervenant_code, intervenant_id) VALUES (intervenant_par_defaut_id_seq.nextval, :annee, :code, :id)";
            $this->getEntityManager()->getConnection()->executeUpdate($sql, [
                'annee' => $intervenant->getAnnee()->getId(),
                'code'  => $intervenant->getCode(),
                'id'    => $intervenant->getId(),
            ]);
        } else {
            $sql = "DELETE FROM intervenant_par_defaut WHERE annee_id = :annee AND intervenant_code = :code";
            $this->getEntityManager()->getConnection()->executeUpdate($sql, [
                'annee' => $intervenant->getAnnee()->getId(),
                'code'  => $intervenant->getCode(),
            ]);
        }
    }



    /**
     * Permet de savoir si oui ou non cette fiche intervenant est définie comme étant celle par défaut.
     *
     * @param Intervenant $intervenant
     *
     * @return bool
     */
    public function estDefiniParDefaut (Intervenant $intervenant): bool
    {
        return $this->getIntervenantIdParDefaut($intervenant->getCode(), $intervenant->getAnnee()->getId()) === $intervenant->getId();
    }



    /**
     * @param $intervenant Intervenant
     *
     * @return Intervenant[]
     */
    public function getIntervenants (Intervenant $intervenant): array
    {
        $findParams = ['code' => $intervenant->getCode(), 'annee' => $intervenant->getAnnee()];
        $repo       = $this->getRepo();

        $result = $repo->findBy($findParams);

        return $result;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias ()
    {
        return 'int';
    }



    /**
     * @return Intervenant
     */
    public function newEntity (): Intervenant
    {
        $intervenant = parent::newEntity();
        $intervenant->setStructure($this->getServiceContext()->getStructure());
        $intervenant->setStatut($this->getServiceStatut()->getAutres());
        $intervenant->setAnnee($this->getServiceContext()->getAnnee());
        $intervenant->setSource($this->getServiceSource()->getOse());
        $intervenant->setCode(uniqid('OSE'));
        $intervenant->setSourceCode($intervenant->getCode());

        return $intervenant;
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy (?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.nomUsuel, $alias.prenom");

        return $qb;
    }



    /**
     * Filtre par le type d'intervenant
     *
     * @param TypeIntervenant   $typeIntervenant Type de l'intervenant
     * @param QueryBuilder|null $queryBuilder
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function finderByType (TypeIntervenant $typeIntervenant, ?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $sStatut = $this->getServiceStatut();

        $this->join($sStatut, $qb, 'statut', false, $alias);
        $sStatut->finderByTypeIntervenant($typeIntervenant, $qb);

        return $qb;
    }



    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param Intervenant $entity Entité à détruire
     * @param bool        $softDelete
     *
     * @return self
     */
    public function delete ($entity, $softDelete = true)
    {
        if (!$softDelete) {
            $id   = (int)$entity->getId();
            $sqls = [];

            $sqls[] = "
            DELETE FROM volume_horaire vh
              WHERE 
                service_id IN (SELECT id FROM service s WHERE 
                  intervenant_id = $id
                  AND s.histo_destruction IS NOT NULL 
                )
            ";

            $sqls[] = "
            DELETE FROM volume_horaire_ref vh
              WHERE 
                service_referentiel_id IN (SELECT id FROM service_referentiel s WHERE 
                  intervenant_id = $id
                  AND s.histo_destruction IS NOT NULL 
                )
            ";

            $depTables = [
                'INDIC_MODIF_DOSSIER',
                'AGREMENT',
                'CONTRAT',
                'INTERVENANT_DOSSIER',
                'MODIFICATION_SERVICE_DU',
                'PIECE_JOINTE',
                'SERVICE_REFERENTIEL',
                'AFFECTATION_RECHERCHE',
                'SERVICE',
                'VALIDATION',
            ];

            foreach ($depTables as $depTable) {
                $sqls[] = "
                DELETE FROM
                  $depTable
                WHERE
                  intervenant_id = $id
                  AND histo_destruction IS NOT NULL";
            }

            foreach ($sqls as $sql) {
                $this->getEntityManager()->getConnection()->executeQuery($sql);
            }
        }

        return parent::delete($entity, $softDelete); // TODO: Change the autogenerated stub
    }



    public function getSystemeInformationUrl (Intervenant $intervenant): ?string
    {
        if (!$intervenant->getSource()->getImportable()) return null;

        $url = Application::getInstance()->config()['ldap']['systemeInformationUrl'] ?? null;
        if (!$url) return null;

        $hydrator = new ClassMethodsHydrator();
        $data     = $hydrator->extract($intervenant);
        foreach ($data as $key => $value) {
            if (false !== strpos($url, ':' . $key)) {
                $url = str_replace(':' . $key, $value, $url);
            }
        }

        return $url;
    }



    /**
     * @param string    $nom
     * @param string    $prenom
     * @param \DateTime $dateNaissance
     * @param array     $params
     *
     * Params :
     *   code   : null | string                     => généré si non fourni
     *   annee  : null | int | Annee                => Année en cours si non fournie
     *   statut : null | string | Statut => AUTRES si non fourni, si string alors c'est le code du statut
     *
     * @return Intervenant
     */
    public function creerIntervenant (string $nom, string $prenom, \DateTime $dateNaissance, array $params = []): Intervenant
    {
        if (!isset($params['code']) || empty($params['code'])) {
            $params['code'] = uniqid('OSE');
        }

        if (!isset($params['annee']) || empty($params['annee'])) {
            $params['annee'] = $this->getServiceContext()->getAnnee();
        } elseif (!$params['annee'] instanceof Annee) {
            $params['annee'] = $this->getServiceAnnee()->get($params['annee']);
        }

        if (!isset($params['statut']) || empty($params['statut'])) {
            $params['statut'] = $this->getServiceStatut()->getAutres();
        } elseif (!$params['statut'] instanceof Statut) {
            $params['statut'] = $this->getServiceStatut()->getByCode($params['statut'], $params['annee']);
        }

        $intervenant = new Intervenant;

        $intervenant->setCode($params['code']);
        $intervenant->setSource($this->getServiceSource()->getOse());
        $intervenant->setSourceCode($params['code']);

        $intervenant->setNomUsuel($nom);
        $intervenant->setPrenom($prenom);
        $intervenant->setDateNaissance($dateNaissance);
        $intervenant->setAnnee($params['annee']);
        $intervenant->setStatut($params['statut']);

        $this->save($intervenant);

        $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);

        return $intervenant;
    }



    public function getByCode (string $code): ?Intervenant
    {
        $anneeId     = $this->getServiceContext()->getAnnee()->getId();
        $statutId    = null;
        $structureId = $this->getServiceContext()->getStructure();
        if ($structureId) $structureId = $structureId->getId();

        $bones = $this->getBones(['CODE' => $code, 'ANNEE_ID' => $anneeId]);

        return $this->bestIntervenantByBones($bones, $code, $anneeId, $statutId, $structureId);
    }



    /**
     * Sauvegarde une entité
     *
     * @param Intervenant $entity
     *
     * @return Intervenant
     * @throws \RuntimeException
     */
    public function save ($entity)
    {
        if (!$entity->getSource()) {
            $entity->setSource($this->getServiceSource()->getOse());
        }
        if (!$entity->getAnnee()) {
            $entity->setAnnee($this->getServiceContext()->getAnnee());
        }

        return parent::save($entity);
    }



    public function updateExportDate (Intervenant $intervenant): Intervenant
    {
        $date = new \DateTime();
        $intervenant->setExportDate($date);
        $this->getEntityManager()->persist($intervenant);
        $this->getEntityManager()->flush();

        return $intervenant;
    }



    public function updateCode (Intervenant $intervenant, $code): Intervenant
    {
        if (!empty($code)) {
            $intervenant->setCode($code);
            $this->getEntityManager()->persist($intervenant);
            $this->getEntityManager()->flush();
            //On recalcul les tablaeux de bords de l'intervenant
            $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);
        }

        return $intervenant;
    }

    public function updateCodeRh(Intervenant $intervenant, $codeRh): Intervenant
    {
        if (!empty($codeRh)) {
            $intervenant->setCodeRh($codeRh);
            $this->getEntityManager()->persist($intervenant);
            $this->getEntityManager()->flush();
            //On recalcul les tablaeux de bords de l'intervenant
            $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);
        }

        return $intervenant;
    }



    public function updateSource (Intervenant $intervenant): Intervenant
    {
        $config = Application::getInstance()->config()['export-rh'] ?? [];
        if (!empty($config['sync-source'])) {
            //On regarde si le code fourni correspond bien à une source valide
            $source = $this->getServiceSource()->getByCode($config['sync-source']);
            if (!empty($source)) {
                $intervenant->setSource($source);
                $this->getEntityManager()->persist($intervenant);
                $this->getEntityManager()->flush();
                //On recalcul les tablaeux de bords de l'intervenant
                $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);
            }
        }

        return $intervenant;
    }

}
