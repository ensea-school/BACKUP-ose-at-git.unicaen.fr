<?php

namespace Application\Controller;

use Application\Acl\AdministrateurRole;
use Application\Controller\Plugin\Context;
use Application\Entity\Db\NotificationIndicateur as NotificationIndicateurEntity;
use Application\Interfaces\StructureAwareInterface;
use Application\Service\Indicateur as IndicateurService;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Application\Service\Indicateur\DateAwareIndicateurImplInterface;
use Application\Service\NotificationIndicateur as NotificationIndicateurService;
use Common\Exception\MessageException;
use Common\Exception\RuntimeException;
use DateTime;
use Doctrine\ORM\EntityManager;
use Zend\Console\Request as ConsoleRequest;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Submit;
use Zend\Form\Form;
use Zend\Mail\Message as MailMessage;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Uri\Http as HttpUri;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

/**
 * Opérations autour des notifications.
 *
 * @method EntityManager em()
 * @method Context              context()
 * @method \UnicaenApp\Controller\Plugin\Mail mail()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NotificationController extends AbstractActionController
{
    use \Application\Service\Traits\ContextAwareTrait;
    
    /**
     * Visualisation de tous les abonnements aux indicateurs.
     * 
     * @return ViewModel
     */
    public function indicateursAction()
    {
        $nis = $this->getServiceNotificationIndicateur()->findNotificationsIndicateurs(false);
        
        $viewModel = new ViewModel();
        $viewModel
                ->setVariable('nis', $nis)
                ->setVariable('serviceIndicateur', $this->getServiceIndicateur());
        
        return $viewModel;
    }
    
    /**
     * Réponse aux requêtes AJAX d'obtention du "title" (intitulé contenant le nombre de résultats trouvés)
     * d'un indicateur.
     * 
     * @return JsonModel
     */
    public function indicateurFetchTitleAction()
    {
        $niId = $this->params()->fromQuery('id');
        if (!$niId) {
            exit;
        }
        
        $ni             = $this->getServiceNotificationIndicateur()->get($niId);
        $indicateurImpl = $this->getServiceIndicateur()->getIndicateurImpl($ni->getIndicateur(), $ni->getStructure());
        
        if ($indicateurImpl->getResultCount()) {
            $url = $this->url()->fromRoute('indicateur/result', [
                'indicateur' => $ni->getIndicateur()->getId(),
                'structure'  => $ni->getStructure() ? $ni->getStructure()->getId() : null,
            ]);
            $title = <<<EOS
<a href="$url" title="Cliquez pour vous rendre à la page concernée">$indicateurImpl</a>
EOS;
        } 
        else {
            $title = (string) $indicateurImpl;
        }

        return new JsonModel([
            'title' => $title,
        ]);
    }
    
    /**
     * Notifications par mail des personnes abonnées à des indicateurs.
     * 
     * Accessible en ligne de commande, par exemple (on suppose que l'on est situé dans le répertoire de l'appli) :
     *      php public/index.php notifier indicateurs --force --requestUriHost=/localhost/ose --requestUriScheme=http
     * Arguments de la ligne de commande : 
     * - <code>force</code> (facultatif)
     * - <code>requestUriHost</code> (obligatoire),
     * - <code>requestUriScheme</code> (facultatif, "http" par défaut).
     */
    public function notifierIndicateursAction()
    {
        $request  = $this->getRequest();
        $renderer = $this->getServiceLocator()->get('view_manager')->getRenderer();  /* @var $renderer PhpRenderer */
        $force    = (bool) $request->getParam('force');
        $nis      = $this->getServiceNotificationIndicateur()->findNotificationsIndicateurs($force);
        $role     = $this->getServiceContext()->getSelectedIdentityRole();
        
        if ($request instanceof ConsoleRequest) {
            // S'il s'agit d'une requête de type Console (CLI), le plugin de contrôleur Url utilisé par les indicateurs
            // n'est pas en mesure de construire des URL (car le ConsoleRouter ne sait pas ce qu'est une URL!).
            // On injecte donc provisoirement un HttpRouter dans le circuit.
            $event      = $this->getEvent();
            $router     = $event->getRouter();
            $httpRouter = $this->getServiceLocator()->get('HttpRouter'); /* @var $httpRouter TreeRouteStack */
            $event->setRouter($httpRouter);
            
            // De plus, pour fonctionner, le HttpRouter a besoin du "prefixe" à utiliser pour assembler les URL
            // (ex: "http://localhost/ose"). Ce prefixe est fourni via un HttpUri initialisé à partir de 2 arguments 
            // de la ligne de commande : "requestUriHost" (obligatoire) et "requestUriScheme" (facultatif, "http" par défaut).
            $httpUri = (new HttpUri())
                    ->setHost($request->getParam('requestUriHost'))              // ex: "/localhost/ose", "ose.unicaen.fr"
                    ->setScheme($request->getParam('requestUriScheme', "http")); // ex: "http", "https"
            $httpRouter->setRequestUri($httpUri);
        }
        
        foreach ($nis as $ni) { /* @var $ni NotificationIndicateurEntity */
            $indicateurImpl = $this->getServiceIndicateur()->getIndicateurImpl($ni->getIndicateur(), $ni->getStructure());
            
            // certains indicateurs ont besoin d'un critère de date 
            if ($indicateurImpl instanceof DateAwareIndicateurImplInterface) {
                $indicateurImpl->setDate($ni->getDateDernNotif());
            }
        
            // pas de notification si l'indicateur ne renvoit rien
            if (! (int) $indicateurImpl->getResultCount()) {
                continue;
            }
            
            // corps au format HTML
            $html = $renderer->render('application/notification/mail/indicateur', [
                'indicateurImpl' => $indicateurImpl,
                'ni'             => $ni,
            ]);
            $part          = new MimePart($html);
            $part->type    = Mime::TYPE_HTML;
            $part->charset = 'UTF-8';
            $body          = new MimeMessage();
            $body->addPart($part);
        
            // init
            $message       = new MailMessage();
            $message->setEncoding('UTF-8')
                    ->setFrom('ne_pas_repondre@unicaen.fr', "Application " . ($app = $this->appInfos()->getNom()))
                    ->setSubject(sprintf("[%s Notif %s] %s", $app, $ni->getFrequenceToString(), $indicateurImpl->getTitle()))
                    ->setBody($body)
                    ->addTo($ni->getPersonnel()->getEmail(), "" . $ni->getPersonnel());

            // NB: S'il s'agit d'une requête de type Console (CLI), il n'y a pas de rôle courant.
            if ($role && $role->getPersonnel()) {
                $message->addCc($role->getPersonnel()->getEmail(), "" . $role->getPersonnel());
            }

            // envoi
            $this->mail()->send($message);
            
            if (!$force) {
                // enregistrement de la date de dernière notification
                $now = new DateTime();
                $now->setTime($now->format('H'), 0, 0); // raz minutes et secondes
                $ni->setDateDernNotif($now);
                $this->em()->flush($ni);
            }
        }
        
        // S'il s'agit d'une requête de type Console (CLI), rétablissement du router initial (cf. commentaires plus haut).
        if ($request instanceof Request) {
            $event->setRouter($router);
        }
        
        exit;
    }
    
    /**
     * 
     * 
     * @return ViewModel
     * @throws MessageException
     */
    public function indicateurIntervenantsAction()
    {
        $role       = $this->getServiceContext()->getSelectedIdentityRole();
        $indicateur = $this->context()->mandatory()->indicateurFromRoute();
        $structure  = $this->context()->structureFromRoute();
        
        if (! $role instanceof AdministrateurRole) {
            $structure = null;
        }
        
        $indicateurImpl = $this->getServiceIndicateur()->getIndicateurImpl($indicateur, $structure ?: $this->getStructure());
        if (! $indicateurImpl instanceof AbstractIntervenantResultIndicateurImpl) {
            throw new RuntimeException("Indicateur non pris en charge.");
        }
        
        $emails = $indicateurImpl->getResultEmails();
        if (! $emails) {
            throw new MessageException("Aucun destinataire trouvé.");
        }
        
        $mailer  = new IndicateurIntervenantsMailer($this, $indicateurImpl);
        $from    = $mailer->getFrom();
        $subject = $mailer->getDefaultSubject();
        $body    = $mailer->getDefaultBody();

        $form = new Form();
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->add((new Text('from'))->setValue($from));
        $form->add((new Text('subject'))->setValue($subject));
        $form->add((new Textarea('body'))->setValue($body));
        $form->add((new Submit('submit')));
        $form->getInputFilter()->get('subject')->setRequired(true);
        $form->getInputFilter()->get('body')->setRequired(true);
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if ($form->setData($post)->isValid()) {
                $mailer->send($emails, $post);
            }
        }
        
        return new ViewModel([
            'title'   => "Envoyer un mail aux intervenants",
            'count'   => count($emails),
            'subject' => $subject,
            'body'    => $body,
            'form'    => $form,
        ]);
    }


    /**
     * Test d'envoi de mail à l'utilisateur connecté.
     * Permet de vérifier que l'envoi de mail fonctionne sur le serveur.
     */
    public function testSendMailAction()
    {
        $role          = $this->getServiceContext()->getSelectedIdentityRole();
        $html          = '<h1>Test d\'envoi de mail</h1><p>Ceci est un test, merci de ne pas en tenir compte.</p>';
        $part          = new MimePart($html);
        $part->type    = Mime::TYPE_HTML;
        $part->charset = 'UTF-8';
        $body          = new MimeMessage();
        $body->addPart($part);

        // init
        $message = new MailMessage();
        $message->setEncoding('UTF-8')
            ->setFrom('ne_pas_repondre@unicaen.fr', "Application " . ($app = $this->appInfos()->getNom()))
            ->setSubject(sprintf("[%s] %s", $app, "Test"))
            ->setBody($body)
            ->addTo($email = $role->getPersonnel()->getEmail(), "" . $role->getPersonnel());

        // envoi
        $this->mail()->send($message);

        echo("Un mail a été envoyé à l'adresse $email.");

        return false;
    }
    
    /**
     * @return StructureEntity
     */
    private function getStructure()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        
//        if ($role instanceof StructureAwareInterface) {
//            return $role->getStructure();
//        }
//        
//        return null;
        return $role->getStructure();
    }
    
    /**
     * @return IndicateurService
     */
    private function getServiceIndicateur()
    {
        return $this->getServiceLocator()->get('IndicateurService');
    }
    
    /**
     * @return NotificationIndicateurService
     */
    private function getServiceNotificationIndicateur()
    {
        return $this->getServiceLocator()->get('NotificationIndicateurService');
    }
}


/**
 * Classe dédiée à l'envoi des mails aux intervenants retournés par un indicateur.
 */
class IndicateurIntervenantsMailer
{
    private $controller;
    private $indicateurImpl;
    
    public function __construct(NotificationController $controller, AbstractIntervenantResultIndicateurImpl $indicateurImpl)
    {
        $this->controller = $controller;
        $this->indicateurImpl = $indicateurImpl;
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
        $subject = sprintf("%s : %s", $this->controller->appInfos()->getNom(), $this->indicateurImpl->getEntity()->getType());
        
        return $subject;
    }
    
    public function getDefaultBody()
    {
        $role     = $this->controller->getServiceContext()->getSelectedIdentityRole();
        $renderer = $this->controller->getServiceLocator()->get('view_manager')->getRenderer(); /* @var $renderer PhpRenderer */
        
        // corps au format HTML
        $html = $renderer->render('application/notification/mail/indicateur-intervenants', [
            'phrase' => $this->indicateurImpl->getIntervenantMessage(),
            'signature' => $role->getPersonnel(),
            'structure' => $role->getStructure(),
        ]);
        
        return $html;
    }
}
