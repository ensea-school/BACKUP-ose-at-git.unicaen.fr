<?php

namespace Application\Controller;

use Application\Entity\Db\Discipline;
use Application\Form\Traits\DisciplineFormAwareTrait;
use Application\Service\Traits\DisciplineServiceAwareTrait;
use Application\Service\Traits\SourceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Application\Exception\DbException;


/**
 * Description of DisciplineController
 *
 * @method EntityManager em()
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class DisciplineController extends AbstractActionController
{
    use DisciplineServiceAwareTrait;
    use SourceAwareTrait;
    use DisciplineFormAwareTrait;



    public function indexAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            Discipline::class,
        ]);
        $qb = $this->getServiceDiscipline()->initQuery()[0];
        $this->getServiceDiscipline()->join($this->getServiceSource(), $qb, 'source', true);

        $disciplines = $this->getServiceDiscipline()->getList($qb);

        return compact('disciplines');
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
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceDiscipline()->save($discipline);
                    $form->get('id')->setValue($discipline->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }
        }

        return compact('form', 'title', 'errors');
    }



    public function supprimerAction()
    {
        $discipline = $this->getEvent()->getParam('discipline');

        $title  = "Suppression de la discipline";
        $form   = new \Application\Form\Supprimer('suppr');
        $errors = [];
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        if ($this->getRequest()->isPost()) {
            try {
                $this->getServiceDiscipline()->delete($discipline);
            } catch (\Exception $e) {
                $e        = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
        }

        return compact('discipline', 'title', 'form', 'errors');
    }
}
