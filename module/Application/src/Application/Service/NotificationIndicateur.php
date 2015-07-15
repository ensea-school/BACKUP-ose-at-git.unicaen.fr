<?php

namespace Application\Service;

use Application\Entity\Db\Indicateur as IndicateurEntity;
use Application\Entity\Db\NotificationIndicateur as NotificationIndicateurEntity;
use Application\Entity\Db\Personnel as PersonnelEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Common\Exception\LogicException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Traits\MessageAwareInterface;
use UnicaenApp\Traits\MessageAwareTrait;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NotificationIndicateur extends AbstractEntityService
{
    use MessageAwareTrait;
    
    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\NotificationIndicateur';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ni';
    }
    
    /**
     * Abonne un personnel à un indicateur.
     * 
     * @param PersonnelEntity $personnel
     * @param IndicateurEntity $indicateur
     * @param string $frequence
     * @param StructureEntity $structure
     * @return NotificationIndicateurEntity
     */
    public function abonner(PersonnelEntity $personnel, IndicateurEntity $indicateur, $frequence, StructureEntity $structure = null)
    {
        if ($frequence && !array_key_exists($frequence, NotificationIndicateurEntity::$frequences)) {
            throw new LogicException("Fréquence spécifiée inconnue: $frequence.");
        }
        
        // recherche d'abonnement existant
        $qb = $this->finderByPersonnel($personnel);
        $this->finderByIndicateur($indicateur, $qb);
        if ($structure) {
            $this->finderByStructure($structure, $qb);
        }
        $abonnement = $qb->getQuery()->getOneOrNullResult();
        
        $structureStr = $structure ? "pour la structure $structure" : null;
        
        // nouvel abonnement
        if (null === $abonnement) {
            $abonnement = new NotificationIndicateurEntity();
            $abonnement
                    ->setPersonnel($personnel)
                    ->setIndicateur($indicateur)
                    ->setFrequence($frequence)
                    ->setStructure($structure)
                    ->setDateAbonnement(new DateTime());
            $this->getEntityManager()->persist($abonnement);
            $this->getEntityManager()->flush($abonnement);
            $message = "Abonnement de $personnel ({$personnel->getEmail()}) $structureStr enregistré avec succès.";
        }
        // une frequence spécifiée = modification d'un abonnement
        elseif ($frequence) {
            if (!array_key_exists($frequence, NotificationIndicateurEntity::$frequences)) {
                throw new LogicException("Fréquence spécifiée inconnue: '$frequence'.");
            }
            $abonnement
                    ->setFrequence($frequence)
                    ->setDateAbonnement(new DateTime());
            $this->getEntityManager()->flush($abonnement);
            $message = "Abonnement de $personnel ({$personnel->getEmail()}) $structureStr modifié avec succès.";
        }
        // aucune frequence spécifiée = désabonnement
        else {
            $this->getEntityManager()->remove($abonnement);
            $message = "Abonnement de $personnel $structureStr supprimé avec succès.";
            $this->getEntityManager()->flush($abonnement);
            $abonnement = null;
        }
            
        $this->addMessage($message, MessageAwareInterface::SUCCESS);
        
        return $abonnement;
    }
    
    /**
     * Recherche des notifications à faire concernant les indicateurs.
     * 
     * La notification est à faire si l'une des conditions suivantes est remplie :
     * - aucune notification n'a encore été faite (i.e. date de dernière notification = null) ;
     * - l'âge de la dernière notification est supérieur à la fréquence de notification.
     * 
     * @param bool $force Si true, toutes les notifications sont considérées comme devant être faites
     * @return QueryBuilder
     */
    public function findNotificationsIndicateurs($force = false)
    {
        $now = new DateTime();
        $now->setTime($now->format('H'), 0, 0); // raz minutes et secondes
        
        $qb = $this->getRepo()->createQueryBuilder("ni")
                ->select("ni, p, i, s")
                ->join("ni.personnel", "p")
                ->join("ni.indicateur", "i", Join::WITH, "i.enabled = 1")
                ->leftJoin("ni.structure", "s")
                ->orderBy("p.nomUsuel, i.type, i.ordre");
        
        if (!$force) {
            $qb
                    ->andWhere("ni.dateDernNotif IS NULL OR ni.dateDernNotif + ni.frequence/(24*60*60) <= :now")
                    ->setParameter('now', $now);
        }
        
        return $qb->getQuery()->getResult();
    }


    /**
     * @return NotificationIndicateurQueryBuilder
     */
    public function createQueryBuilder()
    {
        return new NotificationIndicateurQueryBuilder($this->getEntityManager());
    }
}





//--------------------------------------------------------------------------------------
//
// Expérimentation : expressivité du query builder.
//
//--------------------------------------------------------------------------------------

class NotificationIndicateurQueryBuilder extends QueryBuilder
{
    protected $rootAlias = "ni";

    public function __construct(EntityManagerInterface $em, $rootAlias = null)
    {
        parent::__construct($em);

        $this->rootAlias = $rootAlias ?: $this->rootAlias;
        $this->initWithDefault();
    }

    public function initWithDefault()
    {
        $this
            ->from('Application\Entity\Db\NotificationIndicateur', $this->rootAlias)
            ->select("$this->rootAlias, p, i, s")
            ->join("$this->rootAlias.personnel", "p")
            ->join("$this->rootAlias.indicateur", "i", Join::WITH, "i.enabled = 1")
            ->leftJoin("$this->rootAlias.structure", "s")
            ->orderBy("p.nomUsuel, i.type, i.ordre");

        return $this;
    }

    public function andWhereIndicateurIs(IndicateurEntity $indicateur)
    {
        return $this->applyExpr(new AndWhereIndicateurIs($indicateur, "i")); // alias si pas de jointure: "$this->rootAlias.indicateur"
    }

    public function andWhereIndicateurIsEnabled($enabled = true)
    {
        return $this->applyExpr(new AndWhereIndicateurEnabled($enabled, "i")); // NB: l'alias "$this->rootAlias.indicateur" est impossible ici
    }

    public function andWherePersonnelIs(PersonnelEntity $personnel)
    {
        return $this->applyExpr(new AndWherePersonnelIs($personnel, "p")); // alias si pas de jointure: "$this->rootAlias.personnel"
    }

    public function andWhereStructureIs(StructureEntity $structure)
    {
        return $this->applyExpr(new AndWhereStructurelIs($structure, "s"));
    }

    public function andWhereNotificationNecessaire($notificationNecessaire = true)
    {
        return $this->applyExpr(new AndWhereNotificationNecessaire($notificationNecessaire, "ni"));
    }

    private function applyExpr(AndWhereExpr $expr)
    {
        $expr->applyToQueryBuilder($this);
        return $this;
    }
}

class AndWherePersonnelIs extends AndWhereExpr
{
    public function __construct(PersonnelEntity $entity, $alias)
    {
        parent::__construct($alias);

        $this->where      = "$alias = :personnel";
        $this->parameters = ['personnel' => $entity];
    }

    protected function getJoinSuggestion($rootAlias)
    {
        return sprintf(
            "Peut-être avez-vous oublié de faire la jointure suivante: '->join(\"%s.personnel\", \"%s\")'.",
            $rootAlias,
            $this->alias
        );
    }
}

class AndWhereStructurelIs extends AndWhereExpr
{
    public function __construct(StructureEntity $entity, $alias)
    {
        parent::__construct($alias);

        $this->where      = "$alias = :structure";
        $this->parameters = ['structure' => $entity];
    }
}

class AndWhereNotificationNecessaire extends AndWhereExpr
{
    public function __construct($notificationNecessaire = true, $alias)
    {
        parent::__construct($alias);

        $this->where = $notificationNecessaire ?
            "$alias.dateDernNotif IS NULL OR $alias.dateDernNotif + $alias.frequence/(24*60*60) <= :now" :
            "$alias.dateDernNotif IS NOT NULL AND $alias.dateDernNotif + $alias.frequence/(24*60*60) > :now" ;
        $this->parameters = ['now' => new DateTime()];
    }
}






class AndWhereIndicateurIs extends AndWhereExpr
{
    public function __construct(IndicateurEntity $entity, $alias)
    {
        parent::__construct($alias);

        $this->where      = "$alias = :indicateur";
        $this->parameters = ['indicateur' => $entity];
    }
}

class AndWhereIndicateurEnabled extends AndWhereExpr
{
    public function __construct($enabled = true, $alias)
    {
        parent::__construct($alias);

        $this->where      = "$alias.enabled = :enabled";
        $this->parameters = ['enabled' => (bool) $enabled];
    }
}






abstract class AndWhereExpr
{
    protected $alias;
    protected $where;
    protected $parameters;

    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    public static function instance(array $args = [])
    {
        $expr = new static($args['alias']);
    }

    public function applyToQueryBuilder(QueryBuilder $qb)
    {
        if (! in_array($this->alias, $qb->getAllAliases())) {
            throw new \RuntimeException("L'alias $this->alias est inconnu du QueryBuilder. " . $this->getJoinSuggestion($qb->getRootAlias()));
        }

        $qb->andWhere($this->where);

        foreach ((array) $this->parameters as $name => $value) {
            $qb->setParameter($name, $value);
        }

        return $this;
    }

    protected function getJoinSuggestion($rootAlias)
    {
        return "";
    }
}