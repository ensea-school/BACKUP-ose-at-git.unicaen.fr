<?php

namespace Application\Service;


use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\NiveauEtape;
use Application\Service\Traits\ContextServiceAwareTrait;
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



    public function getEntityClass()
    {
    }



    public function getAlias()
    {
    }



    public function getNeepComplementaire($structure, $niveau, $etape, $annee = null)
    {

        if (is_null($annee)) {
            $annee = $this->getServiceContext()->getAnnee();
        }


        //Source OSE
        $source = $this->getServiceSource()->get('1');


        if (!$structure) return [[], [], []];

        $niveaux  = [];
        $etapes   = [];
        $elements = [];

        $query = $this->em()->createQuery('SELECT
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
              (s = :structure OR ep.structure = :structure) AND e.annee = :annee AND e.source = :source
            ORDER BY
              gtf.ordre, e.niveau
            ');
        $query->setParameter('structure', $structure);
        $query->setParameter('annee', $annee);
        $query->setParameter('source', $source);
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



    protected function em()
    {
        return \Application::$container->get(\Application\Constants::BDD);
    }
}