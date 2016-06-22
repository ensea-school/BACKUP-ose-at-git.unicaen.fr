<?php

namespace Application\Controller;

use Application\Entity\Db\Structure;
use Application\Processus\Traits\IndicateurProcessusAwareTrait;
use Application\Entity\Db\Indicateur;
use Application\Service\Traits\AffectationAwareTrait;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\IndicateurServiceAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\NotificationIndicateurAwareTrait;
use Application\Filter\IntervenantEmailFormatter;
use Doctrine\ORM\Query\Expr\Join;
use Exception;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\Mail\Message as MailMessage;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;



class IndicateurController extends AbstractController
{
    use IndicateurServiceAwareTrait;
    use IntervenantAwareTrait;
    use ContextAwareTrait;
    use NotificationIndicateurAwareTrait;
    use AffectationAwareTrait;
    use IndicateurProcessusAwareTrait;



    /**
     * Liste des indicateurs.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $indicateurs   = $this->getServiceIndicateur()->getList();
        $notifications = $this->getServiceNotificationIndicateur()->getList(
            $this->getServiceNotificationIndicateur()->finderByRole()
        );

        $abonnements = [];
        foreach ($notifications as $notification) {
            $abonnements[$notification->getIndicateur()->getId()] = $notification;
        }

        return compact('indicateurs', 'abonnements');
    }



    public function resultAction()
    {
        $role       = $this->getServiceContext()->getSelectedIdentityRole();
        $indicateur = $this->getEvent()->getParam('indicateur');
        /* @var $indicateur Indicateur */
        $indicateur->setServiceIndicateur($this->getServiceIndicateur());

        $structure = $role->getStructure() ?: $this->getEvent()->getParam('structure');

        /* @var $structure Structure */

        return compact('indicateur', 'structure');
    }



    /**
     * Réponse aux requêtes AJAX d'abonnement de l'utilisateur connecté aux notifications concernant un indicateur.
     *
     * @return JsonModel
     */
    public function abonnerAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->redirect()->toRoute('home');
        }

        $indicateur = $this->getEvent()->getParam('indicateur');
        $frequence  = $this->params()->fromPost('notification');
        $inHome     = $this->params()->fromPost('in-home') == '1';

        $serviceNotif = $this->getServiceNotificationIndicateur();

        try {
            $notificationIndicateur = $serviceNotif->abonner($indicateur, $frequence, $inHome);
            $status                 = 'success';
            $message                = 'Demande prise en compte';
            if (!$notificationIndicateur) {
                $message .= ' (Abonnement supprimé)';
            }
        } catch (Exception $e) {
            $notificationIndicateur = null;
            $status                 = 'error';
            $message                = "Abonnement impossible: {$e->getMessage()}";
        }

        return new JsonModel([
            'status'  => $status,
            'message' => $message,
            'infos'   => $notificationIndicateur ? $notificationIndicateur->getExtraInfos() : null,
        ]);
    }



    /**
     * Indicateurs auxquels est abonné l'utilisateur (un Personnel) spécifié dans la requête.
     *
     * @return ViewModel
     */
    public function abonnementsAction()
    {
        $sab = $this->getServiceNotificationIndicateur();
        $saf = $this->getServiceAffectation();
        $sid = $this->getServiceIndicateur();

        $qb = $sab->finderByRole(); // filtre selon le rôle courant
        $sab->join($sid, $qb, 'indicateur', true);
        $sab->finderByInHome(true, $qb);

        $sab->join($saf, $qb, 'affectation');
        $saf->finderByHistorique($qb);

        $sid->orderBy($qb);

        $notifications = $sab->getList($qb);

        $indicateurs = [];
        foreach( $notifications as $notification ){
            $indicateurs[] = $notification->getIndicateur()->setServiceIndicateur($sid);
        }

        return compact('indicateurs');
    }



    public function envoiMailIntervenantsAction()
    {
        $indicateur = $this->getEvent()->getParam('indicateur');
        /* @var $indicateur Indicateur */
        $indicateur->setServiceIndicateur($this->getServiceIndicateur());

        $intervenantsStringIds = $this->params()->fromQuery('intervenants', $this->params()->fromPost('intervenants', null));
        if ($intervenantsStringIds){
            $intervenantsIds = explode('-', $intervenantsStringIds);
        }else{
            $intervenantsIds = [];
        }


        $result       = $indicateur->getResult();
        $intervenants = [];
        foreach ($result as $index => $indicRes) {
            $intervenant = $indicRes->getIntervenant();
            if (empty($intervenantsIds) || in_array($intervenant->getId(), $intervenantsIds)) {
                $intervenants[$intervenant->getId()] = $intervenant;
            }
        }

        $formatter = new IntervenantEmailFormatter();
        $emails    = $formatter->filter($intervenants);
        if (($intervenantsWithNoEmail = $formatter->getIntervenantsWithNoEmail())) {
            throw new \LogicException(
                "Aucune adresse mail trouvée pour l'intervenant suivant: " . implode(", ", Util::collectionAsOptions($intervenantsWithNoEmail)));
        }

        $mailer  = new IndicateurIntervenantsMailer($this, $indicateur);
        $from    = $mailer->getFrom();
        $subject = $mailer->getDefaultSubject();
        $body    = $mailer->getDefaultBody();

        $form = new Form();
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->add((new Text('from'))->setValue($from));
        $form->add((new Text('nombre'))->setValue(count($emails)));
        $form->add((new Text('subject'))->setValue($subject));
        $form->add((new Textarea('body'))->setValue($body));
        $form->add((new Hidden('intervenants'))->setValue($intervenantsStringIds));
        $form->getInputFilter()->get('subject')->setRequired(true);
        $form->getInputFilter()->get('body')->setRequired(true);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if ($form->setData($post)->isValid()) {
                $mailer->send($emails, $post);
                $count = count($intervenants);
                $pluriel = $count > 1 ? 's' : '';
                $this->flashMessenger()->addSuccessMessage("Le mail a été envoyé à $count intervenant$pluriel");
                $this->redirect()->toRoute('indicateur/result', ['indicateur' => $indicateur->getId()]);
            }
        }

        return [
            'title'   => "Envoyer un mail aux intervenants",
            'count'   => count($emails),
            'form'    => $form,
        ];
    }



    /**
     * Notifications par mail des personnes abonnées à des indicateurs.
     *
     * Accessible en ligne de commande, par exemple (on suppose que l'on est situé dans le répertoire de l'appli) :
     *      php public/index.php notifier indicateurs --force
     * Arguments de la ligne de commande :
     * - <code>force</code> (facultatif)
     * - <code>requestUriHost</code> (obligatoire),
     */
    public function envoiNotificationsAction()
    {
        $request = $this->getRequest();

        $force = (bool)$request->getParam('force');

        $this->getProcessusIndicateur()->envoiNotifications($force);
        
        exit;
    }

}







/**
 * Classe dédiée à l'envoi des mails aux intervenants retournés par un indicateur.
 */
class IndicateurIntervenantsMailer
{
    private $controller;

    private $indicateur;



    public function __construct(AbstractController $controller, Indicateur $indicateur)
    {
        $this->controller = $controller;
        $this->indicateur = $indicateur;
    }



    public function send($emails, $data)
    {
        foreach ($emails as $email => $name) {
            $message = $this->createMessage($data);
            $message->setTo($email, $name);

            $this->controller->mail()->send($message);
        }
    }



    private function createMessage($data)
    {
        // corps au format HTML
        $html          = $data['body'];
        $part          = new MimePart($html);
        $part->type    = Mime::TYPE_HTML;
        $part->charset = 'UTF-8';
        $body          = new MimeMessage();
        $body->addPart($part);

        return (new MailMessage())
            ->setEncoding('UTF-8')
            ->setFrom($this->getFrom(), "Contact Application " . ($app = $this->controller->appInfos()->getNom()))
            ->setSubject($data['subject'])
            ->setBody($body);
    }



    public function getFrom()
    {
        $from = $this->controller->getServiceContext()->getSelectedIdentityRole()->getPersonnel()->getEmail();

        return $from;
    }



    public function getDefaultSubject()
    {
        $subject = sprintf("%s %s : %s",
            $this->controller->appInfos()->getNom(),
            $this->controller->getServiceContext()->getAnnee(),
            strip_tags($this->indicateur->getType())
        );

        return $subject;
    }



    public function getDefaultBody()
    {
        $role     = $this->controller->getServiceContext()->getSelectedIdentityRole();
        $renderer = $this->controller->getServiceLocator()->get('view_manager')->getRenderer();
        /* @var $renderer PhpRenderer */

        // corps au format HTML
        $html = $renderer->render('application/indicateur/mail/intervenants', [
            'phrase'    => $this->indicateur->getMessage(),
            'signature' => $role->getPersonnel(),
            'structure' => $role->getStructure(),
        ]);

        return $html;
    }
}
