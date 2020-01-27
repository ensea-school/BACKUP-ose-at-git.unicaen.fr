<?php

namespace Application\Service;


use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\NiveauEtape;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;

/**
 * Description of OffreFormationService
 *
 *
 */
class OffreFormationService extends AbstractEntityService
{
    use ContextServiceAwareTrait;
    use SourceServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use LocalContextServiceAwareTrait;



    public function getEntityClass()
    {
    }



    public function getAlias()
    {
    }



    public function getNeep($structure, $niveau, $etape, $annee = null, $source = null)
    {


        if (is_null($annee)) {
            $annee = $this->getServiceContext()->getAnnee();
        }

        if (!$structure) return [[], [], []];

        $niveaux  = [];
        $etapes   = [];
        $elements = [];

        $sql = 'SELECT
                partial e.{id,code,annee,libelle,sourceCode,niveau,histoDestruction},
                partial tf.{id},
                partial gtf.{id, libelleCourt, ordre},
                partial ep.{id,code,libelle,sourceCode,etape,periode,tauxFoad,fi,fc,fa,tauxFi,tauxFc,tauxFa}
            FROM
              Application\Entity\Db\Etape e
              JOIN e.structure s
              JOIN e.typeFormation tf
              JOIN tf.groupe gtf
              LEFT JOIN e.elementPedagogique ep
            WHERE
              (s = :structure OR ep.structure = :structure) AND e.annee = :annee ';

        if (!empty($source)) {
            $sql .= 'AND e.source = :source ';
        }

        $sql .= 'ORDER BY
              gtf.ordre, e.niveau';

        $query = $this->getEntityManager()->createQuery($sql);

        $query->setParameter('structure', $structure);
        $query->setParameter('annee', $annee);

        if (!empty($source)) {
            $query->setParameter('source', $source);
        }

        $result = $query->getResult();

        foreach ($result as $object) {
            if ($object instanceof Etape) {
                $n = NiveauEtape::getInstanceFromEtape($object);
                if ($object->estNonHistorise()) {
                    $niveaux[$n->getId()] = $n;
                }
                if (!$niveau || $niveau->getId() == $n->getId()) {
                    if ($object->estNonHistorise() || $object->getElementPedagogique()->count() > 0) {
                        $etapes[] = $object;
                    }
                    if (!$etape || $etape === $object) {
                        foreach ($object->getElementPedagogique() as $ep) {
                            $elements[$ep->getId()] = $ep;
                        }
                    }
                }
            }
        }

        /* Tris */
        uasort($etapes, function (Etape $e1, Etape $e2) {
            $e1Lib = ($e1->getElementPedagogique()->isEmpty() ? 'a_' : 'z_') . strtolower(trim($e1->getLibelle()));
            $e2Lib = ($e2->getElementPedagogique()->isEmpty() ? 'a_' : 'z_') . strtolower(trim($e2->getLibelle()));

            return $e1Lib > $e2Lib;
        });

        uasort($elements, function (ElementPedagogique $e1, ElementPedagogique $e2) {
            $e1Lib = strtolower(trim($e1->getEtape()->getLibelle() . ' ' . $e1->getLibelle()));
            $e2Lib = strtolower(trim($e2->getEtape()->getLibelle() . ' ' . $e2->getLibelle()));

            return $e1Lib > $e2Lib;
        });

        return [$niveaux, $etapes, $elements];
    }



    /**
     * @return array
     */

    public function getOffreComplementaire($structure, $niveau, $etape)
    {
        $offresComplementaires = [];
        $anneeEnCours          = $this->getServiceContext()->getAnnee();
        $anneeSuivante         = $this->getServiceAnnee()->getSuivante($anneeEnCours);
        $source                = $this->getServiceSource()->getOse();

        $this->getServiceLocalContext()
            ->setStructure($structure)
            ->setNiveau($niveau)
            ->setEtape($etape);


        //Offre année en cours
        list($niveaux, $etapes, $elements) = $this->getNeep($structure, $niveau, $etape, $anneeEnCours, $source);
        //Offre année suivante
        list($niveauxN1, $etapesN1, $elementsN1) = $this->getNeep($structure, $niveau, $etape, $anneeSuivante, $source);

        //Organisation pour traitement dans la vue
        $codesEtapeN1          = [];
        $codesElementN1        = [];
        $etapesNonReconduits   = array_diff($etapes, $etapesN1);
        $elementsNonReconduits = array_diff($elements, $elementsN1);

        $reconductionTotale = 'non';
        if (empty($etapesNonReconduits) && empty($elementsNonReconduits)) {
            $reconductionTotale = 'oui';
        }

        foreach ($elementsN1 as $v) {
            $codesElementN1[] = $v->getCode();
        }
        foreach ($etapesN1 as $v) {
            $codesEtapeN1[] = $v->getCode();
        }

        foreach ($etapes as $v) {

            /*if (!$v->isFromSourceOse()) {
                continue;
            }*/
            $offresComplementaires[$v->getId()]['reconduction_partiel'] = 'non';
            $offresComplementaires[$v->getId()]['reconduction']         = (in_array($v->getCode(), $codesEtapeN1)) ? 'oui' : 'non';
            $offresComplementaires[$v->getId()]['etape']                = $v;
            $offresComplementaires[$v->getId()]['elements_pedagogique'] = [];
        }

        foreach ($elements as $v) {

            /*if (!$v->getEtape()->isFromSourceOse()) {
                continue;
            }*/

            $etapeId = $v->getEtape()->getId();

            if (!in_array($v->getCode(), $codesElementN1)) {
                $offresComplementaires[$etapeId]['reconduction_partiel'] = 'oui';
            }

            $offresComplementaires[$etapeId]['elements_pedagogique'][$v->getId()]['reconduction'] = (in_array($v->getCode(), $codesElementN1)) ? 'oui' : 'non';
            $offresComplementaires[$etapeId]['elements_pedagogique'][$v->getId()]['element']      = $v;
        }

        $mappingEtape = $this->createMappingEtapeNEtapeN1($etapes, $etapesN1);

        return [$offresComplementaires, $mappingEtape, $reconductionTotale];
    }



    public function createMappingEtapeNEtapeN1($etapesN, $etapesN1)
    {
        $codesEtapeN  = [];
        $codesEtapeN1 = [];
        $mappingEtape = [];


        foreach ($etapesN1 as $v) {
            $codesEtapeN1[$v->getCode()] = $v->getId();
        }

        foreach ($etapesN as $v) {
            $codesEtapeN[$v->getCode()] = $v->getId();
        }

        foreach ($codesEtapeN as $k => $v) {
            if (array_key_exists($k, $codesEtapeN1)) {
                $mappingEtape[$v] = $codesEtapeN1[$k];
            }
        }

        return $mappingEtape;
    }

}