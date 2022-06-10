<?php

namespace Application\Service;

use Application\Entity\Db\Contrat;
use Application\Entity\Db\ModeleContrat;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Unicaen\OpenDocument\Document;
use Laminas\Mail\Message as MailMessage;
use Laminas\Mime\Message;
use Laminas\Mime\Mime;
use Laminas\Mime\Part;

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

    use DossierServiceAwareTrait;
    use ContextServiceAwareTrait;


    /**
     * @var array
     */
    private $config;



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
            return $this->getRank($m1, $contrat) < $this->getRank($m2, $contrat) ? 1 : 0;
        });

        $modele = reset($modeles);

        return $modele;
    }



    public function generer(Contrat $contrat, $download = true)
    {
        $fileName = sprintf(($contrat->estUnAvenant() ? 'avenant' : 'contrat') . "_%s_%s_%s.pdf",
            ($contrat->getStructure() == null ? null : $contrat->getStructure()->getCode()),
            $contrat->getIntervenant()->getNomUsuel(),
            $contrat->getIntervenant()->getCode());

        $modele = $this->getByContrat($contrat);

        if (!$modele) {
            throw new \Exception('Aucun modèle ne correspond à ce contrat');
        }

        $document = new Document();
        if (isset($this->config['host'])) {
            $document->setHost($this->config['host']);
        }
        if (isset($this->config['tmp-dir'])) {
            $document->setTmpDir($this->config['tmp-dir']);
        }

        if ($modele->hasFichier()) {
            $document->loadFromData(stream_get_contents($modele->getFichier(), -1, 0));
        } else {
            $document->loadFromFile($this->getModeleGeneriqueFile(), true);
        }

        if ($contrat->estUnProjet()) {
            $document->getStylist()->addFiligrane('PROJET');
        }
        $document->getPublisher()->setAutoBreak(true);
        $document->publish($this->generateData($modele, $contrat));
        $document->setPdfOutput(true);
        if ($download) {
            $document->download($fileName);
        } else {

            return $document;
        }
    }



    private function generateData(ModeleContrat $modele, Contrat $contrat)
    {
        $connection = $this->getEntityManager()->getConnection();

        $params = ['contrat' => $contrat->getId()];

        $mainData = $connection->fetchAssociative('SELECT * FROM V_CONTRAT_MAIN WHERE CONTRAT_ID = :contrat', $params);
        if ($modele->getRequete()) {
            $mainDataPerso = $connection->fetchAssociative($modele->getRequete(), $params);
            foreach ($mainDataPerso as $key => $value) {
                // if ($value) {
                $mainData[$key] = $value;
                // }
            }
        }

        $data = [0 => $mainData];

        $blocs = $modele->getBlocs();
        foreach ($blocs as $bname => $bquery) {
            $bdata = $connection->fetchAllAssociative($bquery, $params);
            $bkey  = $bname . '@table:table-row';

            $data[0][$bkey] = $bdata;
        }

        if (!isset($data[0]['serviceCode@table:table-row'])
            && !isset($data[0]['serviceComposante@table:table-row'])
            && !isset($data[0]['serviceLibelle@table:table-row'])
            && !isset($data[0]['serviceHeures@table:table-row'])
        ) {
            $data[0]['serviceCode@table:table-row'] =
                $connection->fetchAllAssociative('SELECT * FROM V_CONTRAT_SERVICES WHERE CONTRAT_ID = :contrat', $params);
        }

        if (isset($mainData['exemplaire1']) && $mainData['exemplaire1'] && ('0' !== $mainData['exemplaire1'])) {
            $data[0]['exemplaire'] = $mainData['exemplaire1'];
            unset($mainData['exemplaire1']);
        }
        if (isset($mainData['exemplaire2']) && $mainData['exemplaire2'] && ('0' !== $mainData['exemplaire2'])) {
            $data[1]               = $data[0];
            $data[1]['exemplaire'] = $mainData['exemplaire2'];
            unset($mainData['exemplaire2']);
        }
        if (isset($mainData['exemplaire3']) && $mainData['exemplaire3'] && ('0' !== $mainData['exemplaire3'])) {
            $data[2]               = $data[0];
            $data[2]['exemplaire'] = $mainData['exemplaire3'];
            unset($mainData['exemplaire3']);
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

        if ($modele->getStatut() && $contrat->getIntervenant()->getStatut()) {
            if ($modele->getStatut()->getCode() == $contrat->getIntervenant()->getStatut()->getCode()) {
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



    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }



    /**
     * @param array $config
     *
     * @return ModeleContratService
     */
    public function setConfig(array $config): ModeleContratService
    {
        $this->config = $config;

        return $this;
    }
}