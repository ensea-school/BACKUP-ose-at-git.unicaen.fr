<?php

namespace OffreFormation\Service;

use Application\Service\AbstractEntityService;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\EtatSortieServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\CheminPedagogique;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Entity\NiveauEtape;
use UnicaenApp\View\Model\CsvModel;

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
    use EtatSortieServiceAwareTrait;


    public function getEntityClass()
    {
    }



    public function getAlias()
    {
    }



    public function getNeep(?Structure $structure, $niveau, $etape, $annee = null, $source = null)
    {
        if ($etape) {
            /* workaroud pour parser les chemins pédagogiques si on fournit une étape spécifique */
            return $this->getNeepEtape($etape);
        }

        if (is_null($annee)) {
            $annee = $this->getServiceContext()->getAnnee();
        }

        if (!$structure) {
            return [[], [], []];
        }

        $niveaux  = [];
        $etapes   = [];
        $elements = [];

        $dql = 'SELECT
                partial e.{id,code,annee,libelle,sourceCode,niveau,histoDestruction},
                partial tf.{id},
                partial gtf.{id, libelleCourt, ordre, pertinenceNiveau},
                partial ep.{id,code,libelle,sourceCode,etape,periode,tauxFoad,fi,fc,fa,tauxFi,tauxFc,tauxFa},
                partial vme.{id,heures, groupes},
                partial s.{id,libelleCourt}
            FROM
              OffreFormation\Entity\Db\Etape e
              JOIN e.structure s
              JOIN e.typeFormation tf
              JOIN tf.groupe gtf
              LEFT JOIN e.elementPedagogique ep
              LEFT JOIN ep.structure epstr
              LEFT JOIN ep.volumeHoraireEns vme
            WHERE
              (s.ids LIKE :structure OR epstr.ids LIKE :structure) AND e.annee = :annee ';

        if (!empty($source)) {
            $dql .= 'AND e.source = :source ';
        }

        $dql .= 'ORDER BY
              gtf.ordre, e.niveau';

        $query = $this->getEntityManager()->createQuery($dql);

        $query->setParameter('structure', $structure->idsFilter());
        $query->setParameter('annee', $annee);

        if (!empty($source)) {
            $query->setParameter('source', $source);
        }

        $result = $query->getResult();

        foreach ($result as $object) {
            if ($object instanceof Etape) {
                $n = NiveauEtape::getInstanceFromEtape($object);
                if ($object->estNonHistorise()) {
                    $gtf = $object->getTypeFormation()->getGroupe()->getPertinenceNiveau();
                    if ($gtf) {
                        $niveaux[$n->getId()] = $n;
                    } else {
                        $niveaux[$n->getLib()] = $n;
                    }
                }
                if (!$niveau || ($niveau->getId() == $n->getId() && $n->getPertinence()) || ($niveau->getLib() == $n->getLib() && !$n->getPertinence())) {
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

            return $e1Lib > $e2Lib ? 1 : 0;
        });

        uasort($elements, function (ElementPedagogique $e1, ElementPedagogique $e2) {
            $e1Lib = strtolower(trim($e1->getEtape()->getLibelle() . ' ' . $e1->getLibelle()));
            $e2Lib = strtolower(trim($e2->getEtape()->getLibelle() . ' ' . $e2->getLibelle()));

            return $e1Lib > $e2Lib ? 1 : 0;
        });


        return [$niveaux, $etapes, $elements];
    }



    public function getNeepEtape($etape)
    {
        $niveaux  = [];
        $etapes   = [];
        $elements = [];

        $dql = 'SELECT
                cp,
                partial e.{id,code,annee,libelle,sourceCode,niveau,histoDestruction},
                partial ep.{id,code,libelle,sourceCode,etape,periode,tauxFoad,fi,fc,fa,tauxFi,tauxFc,tauxFa},
                partial vme.{id,heures, groupes}
            FROM
              OffreFormation\Entity\Db\CheminPedagogique cp
              JOIN cp.etape e
              JOIN cp.elementPedagogique ep
              LEFT JOIN ep.volumeHoraireEns vme
            WHERE
              cp.etape = :etape';

        $query = $this->getEntityManager()->createQuery($dql);

        $query->setParameter('etape', $etape);

        $result = $query->getResult();
        foreach ($result as $object) {
            /** @var CheminPedagogique $object */
            if ($object->estHistorise()) {
                continue;
            }

            $etape   = $object->getEtape();
            $element = $object->getElementPedagogique();

            $n                           = NiveauEtape::getInstanceFromEtape($etape);
            $niveaux[$n->getId()]        = $n;
            $etapes[$etape->getId()]     = $etape;
            $elements[$element->getId()] = $element;
        }

        /* Tri */
        uasort($elements, function (ElementPedagogique $e1, ElementPedagogique $e2) {
            $e1Lib = strtolower(trim($e1->getEtape()->getLibelle() . ' ' . $e1->getLibelle()));
            $e2Lib = strtolower(trim($e2->getEtape()->getLibelle() . ' ' . $e2->getLibelle()));

            return $e1Lib > $e2Lib ? 1 : 0;
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
        [$niveaux, $etapes, $elements] = $this->getNeep($structure, $niveau, $etape, $anneeEnCours);
        //Offre année suivante
        [$niveauxN1, $etapesN1, $elementsN1] = $this->getNeep($structure, $niveau, $etape, $anneeSuivante);

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
            if ($v->getHistoDestruction() != null) {
                continue;
            }
            $offresComplementaires[$v->getId()]['reconduction_partiel'] = 'non';
            $offresComplementaires[$v->getId()]['elements_pedagogique'] = [];
            $offresComplementaires[$v->getId()]['etape']                = $v;
            if ($v->getSource() == $source) {
                $offresComplementaires[$v->getId()]['reconduction']     = (in_array($v->getCode(), $codesEtapeN1)) ? 'oui' : 'non';
                $offresComplementaires[$v->getId()]['reconductionFait'] = (in_array($v->getCode(), $codesEtapeN1)) ? 'oui' : 'non';
            } else {
                $offresComplementaires[$v->getId()]['reconduction']     = 'oui';
                $offresComplementaires[$v->getId()]['reconductionFait'] = 'non';
            }
        }

        foreach ($elements as $v) {

            if ($v->getSource() != $source || $v->getHistoDestruction() != null || $v->getEtape()->getHistoDestruction() != null) {
                continue;
            }
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

    public function generateCsvExport(?Structure $structure, ?NiveauEtape $niveau, ?Etape $etape): CsvModel
    {
        /* Préparation et affichage */
        $etatSortie = $this->getServiceEtatSortie()->getRepo()->findOneBy(['code' => 'export-offre-formation']);

        $fileName = 'Export-offre-formation - ' . date('dmY') . '.csv';

        //$filters             = $recherche->getFilters();
        $filters['ANNEE_ID'] = '2025';
        /*if ($structure) {
            $filters['STRUCTURE_AFF_IDS OR STRUCTURE_ENS_IDS'] = $structure->idsFilter();
        }

        $options = [
            'annee'               => $annee->getLibelle(),
            'type_volume_horaire' => $recherche->getTypeVolumeHoraire()->getLibelle(),
            'etat_volume_horaire' => $recherche->getEtatVolumeHoraire()->getLibelle(),
            'composante'          => $recherche->getStructureAff() ? $recherche->getStructureAff()->getLibelleCourt() : 'Toutes',
            'type_intervenant'    => $recherche->getTypeIntervenant() ? $recherche->getTypeIntervenant()->getLibelle() : 'Tous intervenants',
        ];*/


        $csv = $this->getServiceEtatSortie()->genererCsv($etatSortie, $filters, []);
        $csv->setFilename($fileName);


        //$elements = $this->getNeep($structure, $niveau, $etape)[2];

        /*$headers = [
            'Structure',
            'Code formation',
            'Libellé formation',
            'Niveau',
            'Code enseignement',
            'Libellé enseignement',
            'Code discipline',
            'Libellé discipline',
            'Période',
            'FOAD',
            'Taux FI / effectifs année préc.',
            'Taux FA / effectifs année préc.',
            'Taux FC / effectifs année préc.',
            'Effectifs FI actuels',
            'Effectifs FA actuels',
            'Effectifs FC actuels',
        ];*/

        return $csv;


    }

}
