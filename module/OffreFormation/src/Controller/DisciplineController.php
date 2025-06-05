<?php

namespace OffreFormation\Controller;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Controller\AbstractController;
use Application\Service\Traits\SourceServiceAwareTrait;
use OffreFormation\Entity\Db\Discipline;
use OffreFormation\Form\Traits\DisciplineFormAwareTrait;
use OffreFormation\Service\Traits\DisciplineServiceAwareTrait;


/**
 * Description of DisciplineController
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class DisciplineController extends AbstractController
{
    use DisciplineServiceAwareTrait;
    use SourceServiceAwareTrait;
    use DisciplineFormAwareTrait;
    use ParametresServiceAwareTrait;



    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Discipline::class,
        ]);
        $qb = $this->getServiceDiscipline()->initQuery()[0];
        $this->getServiceDiscipline()->join($this->getServiceSource(), $qb, 'source', true);

        $disciplines = $this->getServiceDiscipline()->getList($qb);

        $libellesCodesCorresp = [];
        for( $i=1;$i<=4;$i++){
            $lcc = $this->getServiceParametres()->get('discipline_codes_corresp_'.$i.'_libelle');
            if ($lcc){
                $libellesCodesCorresp[$i] = $lcc;
            }
        }

        return compact('disciplines','libellesCodesCorresp');
    }



    public function voirAction()
    {
        return [];
    }



    public function saisirAction()
    {
        $discipline = $this->getEvent()->getParam('discipline');
        $errors     = [];

        $form = $this->getFormDiscipline();
        if (empty($discipline)) {
            $title      = 'Création d\'une nouvelle discipline';
            $discipline = $this->getServiceDiscipline()->newEntity();
            $form->setObject($discipline);
        } else {
            $title = 'Édition de la discipine';
            $form->bind($discipline);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceDiscipline()->save($discipline);
                    $form->get('id')->setValue($discipline->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
                    $errors[] = $this->translate($e);
                }
            }
        }

        return compact('form', 'title', 'errors');
    }



    public function supprimerAction()
    {
        $discipline = $this->getEvent()->getParam('discipline');

        $title  = "Suppression de la discipline";

        $form = $this->makeFormSupprimer(function() use($discipline){
            $this->getServiceDiscipline()->delete($discipline);
        });

        return compact('discipline', 'title', 'form');
    }
}
