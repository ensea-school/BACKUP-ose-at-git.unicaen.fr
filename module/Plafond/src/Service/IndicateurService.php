<?php

namespace Plafond\Service;


use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use Indicateur\Entity\Db\Indicateur;
use Plafond\Entity\Db\PlafondPerimetre;

/**
 * Description of IndicateurService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class IndicateurService
{
    use PlafondServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;


    public function makeQuery(Indicateur $indicateur): string
    {
        switch ($indicateur->getTypeIndicateur()->getPlafondPerimetreCode()) {
            case PlafondPerimetre::INTERVENANT:
                return $this->makeQueryIntervenant($indicateur);
            case PlafondPerimetre::STRUCTURE:
                return $this->makeQueryStructure($indicateur);
            case PlafondPerimetre::REFERENTIEL:
                return $this->makeQueryReferentiel($indicateur);
            case PlafondPerimetre::ELEMENT:
                return $this->makeQueryElement($indicateur);
            case PlafondPerimetre::VOLUME_HORAIRE:
                return $this->makeQueryVolumeHoraire($indicateur);
        }

        throw new \Exception('La requête n\'a pas pu être construite : le périmètre de plafond n\'a pas été identifié');
    }



    protected function makeQueryIntervenant(Indicateur $indicateur): string
    {
        $numero = $indicateur->getNumero();

        return "
        SELECT
          v.intervenant_id,
          v.structure_id,
          v.etat \"État\",
          replace(to_char(v.heures),'.',',') \"Heures\",
          replace(to_char(v.plafond),'.',',') \"Plafond\",
          replace(to_char(v.derogation),'.',',') \"Derogation\"
        FROM
          V_INDICATEUR_P_INTERVENANT v
        WHERE
          v.numero = $numero
        ";
    }



    protected function makeQueryStructure(Indicateur $indicateur): string
    {
        $numero = $indicateur->getNumero();

        return "
        SELECT
          v.intervenant_id,
          v.structure_id,
          v.etat \"État\",
          replace(to_char(v.heures),'.',',') \"Heures\",
          replace(to_char(v.plafond),'.',',') \"Plafond\",
          replace(to_char(v.derogation),'.',',') \"Derogation\"
        FROM
          V_INDICATEUR_P_STRUCTURE v
        WHERE
          v.numero = $numero
        ";
    }



    protected function makeQueryReferentiel(Indicateur $indicateur): string
    {
        $numero = $indicateur->getNumero();

        return "
        SELECT
          v.intervenant_id,
          v.structure_id,
          v.etat \"État\",
          v.fonction \"Fonction\",
          replace(to_char(v.heures),'.',',') \"Heures\",
          replace(to_char(v.plafond),'.',',') \"Plafond\",
          replace(to_char(v.derogation),'.',',') \"Derogation\"
        FROM
          V_INDICATEUR_P_REFERENTIEL v
        WHERE
          v.numero = $numero
        ";
    }



    protected function makeQueryElement(Indicateur $indicateur): string
    {
        $numero = $indicateur->getNumero();

        return "
        SELECT
          v.intervenant_id,
          v.structure_id,
          v.etat \"État\",
          v.element \"Élément pédagogique\",
          replace(to_char(v.heures),'.',',') \"Heures\",
          replace(to_char(v.plafond),'.',',') \"Plafond\",
          replace(to_char(v.derogation),'.',',') \"Derogation\"
        FROM
          V_INDICATEUR_P_ELEMENT v
        WHERE
          v.numero = $numero
        ";
    }



    protected function makeQueryVolumeHoraire(Indicateur $indicateur): string
    {
        $numero = $indicateur->getNumero();

        return "
        SELECT
          v.intervenant_id,
          v.structure_id,
          v.etat \"État\",
          v.element \"Élément pédagogique\",
          v.type_intervention \"Type d'intervention\",
          replace(to_char(v.heures),'.',',') \"Heures\",
          replace(to_char(v.plafond),'.',',') \"Plafond\",
          replace(to_char(v.derogation),'.',',') \"Derogation\"
        FROM
          V_INDICATEUR_P_VOLUME_HORAIRE v
        WHERE
          v.numero = $numero
        ";
    }



    public function getIndicateursData()
    {
        $typesVolumesHoraires = $this->getServiceTypeVolumeHoraire()->getList();

        var_dump($typesVolumesHoraires);

        $plafondConfigs = $this->getServicePlafond()->getPlafondsConfig();
        foreach ($plafondConfigs as $plafondConfig) {
            $plafond = $plafondConfig->getPlafond();

            varDump($plafond->getNumero());
        }
    }
}