<?php

namespace Application\Service;

use Application\Entity\Db\Contrat;
use Application\Entity\Db\ModeleContrat;
use Unicaen\OpenDocument\Document;

/**
 * Description of ModeleContratService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method ModeleContrat get($id)
 * @method ModeleContrat[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method ModeleContrat newEntity()
 *
 */
class ModeleContratService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return ModeleContrat::class;
    }



    /**
     * @param Contrat $contrat
     *
     * @return ModeleContrat|null
     */
    public function getByContrat(Contrat $contrat)
    {
        $modeles = $this->getList();

        usort($modeles, function (ModeleContrat $m1, ModeleContrat $m2) use ($contrat) {
            return $this->getRank($m1, $contrat) < $this->getRank($m2, $contrat);
        });

        $modele = reset($modeles);

        return $modele;
    }



    public function generer(Contrat $contrat)
    {
        $fileName = sprintf(($contrat->estUnAvenant() ? 'avenant' : 'contrat') . "_%s_%s_%s.pdf",
            $contrat->getStructure()->getCode(),
            $contrat->getIntervenant()->getNomUsuel(),
            $contrat->getIntervenant()->getCode());

        $modele = $this->getByContrat($contrat);

        if (!$modele) {
            throw new \Exception('Aucun modèle ne correspond à ce contrat');
        }

        $document = new Document();
        $document->setTmpDir(getcwd() . '/data/cache/');

        if ($modele->hasFichier()) {
            $document->loadFromData($modele->getFichier());
        } else {
            $document->loadFromFile($this->getModeleGeneriqueFile(), true);
        }

        if ($contrat->estUnProjet()) {
            $document->getStylist()->addFiligrane('PROJET');
        }
        $document->getPublisher()->setAutoBreak(true);
        $document->publish($this->generateData($modele, $contrat));
        $document->setPdfOutput(true);
        $document->download($fileName);
    }



    /**
     * @return string
     */
    public function getModeleGeneriqueFile(): string
    {
        return getcwd() . '/data/modele_contrat.odt';
    }



    private function generateData(ModeleContrat $modele, Contrat $contrat)
    {
        $connection = $this->getEntityManager()->getConnection();

        $params = ['contrat' => $contrat->getId()];

        $mainData = $connection->fetchAssoc('SELECT * FROM V_CONTRAT_MAIN WHERE CONTRAT_ID = :contrat', $params);
        if ($modele->getRequete()) {
            $mainDataPerso = $connection->fetchAssoc($modele->getRequete(), $params);
            foreach ($mainDataPerso as $key => $value) {
                if ($value) {
                    $mainData[$key] = $value;
                }
            }
        }

        $data = [0 => $mainData];

        $blocs = $modele->getBlocs();
        foreach ($blocs as $bname => $bquery) {
            $bdata = $connection->fetchAll($bquery, $params);
            $bkey  = $bname . '@table:table-row';

            $data[0][$bkey] = $bdata;
        }

        if (!isset($data[0]['serviceCode@table:table-row'])
            && !isset($data[0]['serviceComposante@table:table-row'])
            && !isset($data[0]['serviceLibelle@table:table-row'])
            && !isset($data[0]['serviceHeures@table:table-row'])
        ) {
            $data[0]['serviceCode@table:table-row'] =
                $connection->fetchAll('SELECT * FROM V_CONTRAT_SERVICES WHERE CONTRAT_ID = :contrat', $params);
        }

        $data[1] = $data[0];

        if (isset($mainData['exemplaire1'])) {
            $data[0]['exemplaire'] = $mainData['exemplaire1'];
            unset($mainData['exemplaire1']);
        }
        if (isset($mainData['exemplaire2'])) {
            $data[1]['exemplaire'] = $mainData['exemplaire2'];
            unset($mainData['exemplaire2']);
        }

        return $data;
    }



    private function getRank(ModeleContrat $modele, Contrat $contrat)
    {
        $rank = 100;

        if ($modele->getStructure() && $contrat->getStructure()) {
            if ($modele->getStructure() == $contrat->getStructure()) {
                $rank += 40;
            } else {
                return 0;
            }
        }

        if ($modele->getStatutIntervenant() && $contrat->getIntervenant()->getStatut()) {
            if ($modele->getStatutIntervenant() == $contrat->getIntervenant()->getStatut()) {
                $rank += 55;
            } else {
                return 0;
            }
        }

        return $rank;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'modele_contrat';
    }

}