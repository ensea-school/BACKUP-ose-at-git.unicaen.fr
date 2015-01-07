<?php

namespace Application\Controller;

use Application\Controller\Plugin\Context;
use Application\Entity\Db\NotificationIndicateur as NotificationIndicateurEntity;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\Indicateur as IndicateurService;
use Application\Service\Indicateur\DateAwareIndicateurImplInterface;
use Application\Service\NotificationIndicateur as NotificationIndicateurService;
use DateTime;
use Doctrine\ORM\EntityManager;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mail\Message as MailMessage;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Mime;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Uri\Http as HttpUri;
use Zend\View\Renderer\PhpRenderer;

/**
 * Opérations autour des notifications.
 *
 * @method EntityManager em()
 * @method Context              context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NotificationController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     * Notifications par mail des personnes abonnées à des indicateurs.
     * 
     * Accessible en ligne de commande, par exemple (on suppose que l'on est situé dans le répertoire de l'appli) :
     *      php public/index.php notifier indicateurs --requestUriHost=/localhost/ose --requestUriScheme=http
     * Arguments de la ligne de commande : 
     * - <code>requestUriHost</code> (obligatoire),
     * - <code>requestUriScheme</code> (facultatif, "http" par défaut).
     */
    public function notifierIndicateursAction()
    {
        $request  = $this->getRequest();
        $renderer = $this->getServiceLocator()->get('view_manager')->getRenderer();  /* @var $renderer PhpRenderer */
        $nis      = $this->getServiceNotificationIndicateur()->findNotificationsIndicateurs();
        
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
                    ->setSubject(sprintf("[%s Notif] %s", $app, $indicateurImpl->getTitle()))
                    ->setBody($body)
                    ->addTo($ni->getPersonnel()->getEmail(), "" . $ni->getPersonnel());
                    
            // envoi
            $this->mail()->send($message);
            
            // enregistrement de la date de dernière notification
            $ni->setDateDernNotif(new DateTime());
            $this->em()->flush($ni);
        }
        
        // S'il s'agit d'une requête de type Console (CLI), rétablissement du router initial (cf. commentaires plus haut).
        if ($request instanceof Request) {
            $event->setRouter($router);
        }
        
        exit;
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