<?php

namespace EtatSortie\Hydrator;

use EtatSortie\Entity\Db\EtatSortie;
use Laminas\Hydrator\HydratorInterface;
use Signature\Service\SignatureFlowServiceAwareTrait;

class EtatSortieHydrator implements HydratorInterface
{

    use SignatureFlowServiceAwareTrait;

    /**
     * @param array      $data
     * @param EtatSortie $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setCle($data['cle']);
        $object->setCsvParams($data['csv-params']);
        $object->setPdfTraitement($data['pdf-traitement']);
        $object->setCsvTraitement($data['csv-traitement']);
        $object->setAutoBreak($data['auto-break'] === 'true');
        $object->setRequete($data['requete']);
        $signatureActivation = isset($data['signatureActivation']) && $data['signatureActivation'];
        $object->setSignatureActivation($signatureActivation);
        if (isset($data['signatureCircuit'])) {
            $signatureFlow = $this->getServiceSignatureFlow()->get($data['signatureCircuit']);
            $object->setSignatureCircuit($signatureFlow);
        }
        if (isset($data['fichier']['tmp_name']) && $data['fichier']['tmp_name']) {
            $object->setFichier(file_get_contents($data['fichier']['tmp_name']));
            unlink($data['fichier']['tmp_name']);
        }

        $blocs = [];

        for ($i = 1; $i <= 10; $i++) {
            if (isset($data["bloc-$i-nom"]) && $data["bloc-$i-nom"]
                && isset($data["bloc-$i-requete"]) && $data["bloc-$i-requete"]) {
                $blocs[$data["bloc-$i-nom"]] = [
                    'nom'     => $data["bloc-$i-nom"],
                    'zone'    => $data["bloc-$i-zone"],
                    'requete' => $data["bloc-$i-requete"],
                ];
            }
        }
        $object->setBlocs($blocs);

        return $object;
    }



    /**
     * @param EtatSortie $object
     *
     * @return array
     */
    public function extract($object): array
    {

        $data = [
            'code'                => $object->getCode(),
            'libelle'             => $object->getLibelle(),
            'cle'                 => $object->getCle(),
            'csv-params'          => $object->getCsvParams(),
            'pdf-traitement'      => $object->getPdfTraitement(),
            'csv-traitement'      => $object->getCsvTraitement(),
            'auto-break'          => $object->isAutoBreak() ? 'true' : 'false',
            'requete'             => $object->getRequete(),
            'signatureActivation' => ($object->isSignatureActivation()) ? 1 : 0,
            'signatureCircuit'    => ($object->getSignatureCircuit()) ? $object->getSignatureCircuit()->getId() : null,

        ];

        $blocs = $object->getBlocs();
        $i     = 1;
        foreach ($blocs as $nom => $boptions) {
            $data["bloc-$i-nom"]     = $boptions['nom'];
            $data["bloc-$i-zone"]    = $boptions['zone'];
            $data["bloc-$i-requete"] = $boptions['requete'];
            $i++;
        }

        return $data;
    }
}