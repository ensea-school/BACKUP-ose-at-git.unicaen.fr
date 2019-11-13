<?php

namespace Application\Processus;

use Application\Service\AnneeService;
use Application\Service\CheminPedagogiqueService;
use Application\Service\ContextService;
use Application\Service\ElementPedagogiqueService;
use Application\Service\EtapeService;
use Zend\Debug\Debug;
use Zend\Stdlib\Parameters;

/**
 * Description of ReconductionProcessus
 *
 * @author LECOURTES Anthony <antony.lecourtes@unicaen.fr>
 */
class ReconductionProcessus extends AbstractProcessus
{
    protected $etapeService;
    protected $elementPedagogiqueService;
    protected $cheminPedagogiqueService;
    protected $anneeService;
    protected $contextService;


    public function __construct(EtapeService $etapeService,
                                ElementPedagogiqueService $elementPedagogiqueService,
                                CheminPedagogiqueService $cheminPedagogiqueService,
                                AnneeService $anneeService,
                                ContextService $contextService)
    {
        $this->etapeService = $etapeService;
        $this->elementPedagogiqueService = $elementPedagogiqueService;
        $this->cheminPedagogiqueService = $cheminPedagogiqueService;
        $this->anneeService = $anneeService;
        $this->contextService = $contextService;
    }



    public function reconduction(Parameters $datas)
    {
        if(!empty($datas['etape']))
        {
            $anneeEnCours = $this->contextService->getAnnee();
            $em = $this->getEntityManager();
            $dateDuJour = new \DateTime();
            $dateDuJour->format('d/m/Y');
            foreach($datas['etape'] as $idEtape)
            {
                $etapeEnCours = $this->etapeService->get($idEtape);
                $etapeReconduit= clone $etapeEnCours;
                $etapeReconduit->setAnnee($this->anneeService->getSuivante($anneeEnCours));
                $etapeReconduit->setHistoCreation($dateDuJour);
                $em->persist($etapeReconduit);
                //Reconduction des éléments pédagogiques pour cette étape
                if(array_key_exists($idEtape, $datas['element']))
                {
                    foreach($datas['element'][$idEtape] as $idElement)
                    {
                        $elementEnCours = $this->elementPedagogiqueService->get($idElement);
                        $elementReconduit = clone $elementEnCours;
                        $elementReconduit->setAnnee($this->anneeService->getSuivante($anneeEnCours));
                        $elementReconduit->setEtape($etapeReconduit);
                        $elementReconduit->setHistoCreation($dateDuJour);
                        //ajout de l'élément à l'étape
                        $etapeReconduit->addElementPedagogique($elementReconduit);
                        $em->persist($elementReconduit);
                        //Recondution des chemins pédagogiques
                        $cheminsPedagogique = $elementEnCours->getCheminPedagogique();
                        foreach($cheminsPedagogique as $chemin)
                        {
                            $cheminReconduit = clone $chemin;
                            $cheminReconduit->setElementPedagogique($elementReconduit);
                            $cheminReconduit->setEtape($etapeReconduit);
                            $cheminReconduit->setHistoCreation($dateDuJour);
                            $em->persist($cheminReconduit);
                        }
                        unset($elementReconduit, $elementEnCours);
                    }
                }

                $em->flush();
                unset($etapeEnCours, $etapeReconduit);

            }
        }
        else{
            Throw new \Exception('Aucune donnée à reconduire');
        }

      return true;
    }


}