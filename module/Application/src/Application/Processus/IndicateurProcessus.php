<?php

namespace Application\Processus;

use Application\Entity\Db\NotificationIndicateur;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\NotificationIndicateurAwareTrait;
use Application\View\Renderer\PhpRenderer;
use UnicaenApp\Controller\Plugin\Mail;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Mime;
use Zend\Mail\Message as MailMessage;
use Zend\Mime\Message as MimeMessage;


/**
 * Description of IndicateurProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class IndicateurProcessus implements ServiceLocatorAwareInterface, EntityManagerAwareInterface
{
    use ServiceLocatorAwareTrait;
    use NotificationIndicateurAwareTrait;
    use ContextAwareTrait;
    use EntityManagerAwareTrait;



    public function envoiNotifications($force = false)
    {
        $nis = $this->getServiceNotificationIndicateur()->getNotifications($force);

        foreach ($nis as $ni) {
            $message = $this->creerMailNotification($ni);

            if ($message) {
                $this->getMail()->send($message);

                if (!$force) {
                    // enregistrement de la date de dernière notification
                    $now = new \DateTime();
                    $now->setTime($now->format('H'), 0, 0); // raz minutes et secondes
                    $ni->setDateDernNotif($now);
                    $this->getServiceNotificationIndicateur()->save($ni);
                }
            }
        }
        
        return $nis;
    }



    protected function creerMailNotification(NotificationIndicateur $notification)
    {
        $result = $notification->getIndicateur()->getResult($notification->getAffectation()->getStructure());
        $count  = count($result);

        if (0 == $count) return null; // pas de notification pour cet indicateur

        $renderer = $this->getServiceLocator()->get('view_manager')->getRenderer();
        /* @var $renderer PhpRenderer */

        $html          = $renderer->render('application/indicateur/mail/notification', [
            'notification' => $notification,
        ]);
        $part          = new MimePart($html);
        $part->type    = Mime::TYPE_HTML;
        $part->charset = 'UTF-8';
        $body          = new MimeMessage();
        $body->addPart($part);

        // init
        $message = new MailMessage();
        $message->setEncoding('UTF-8')
            ->setFrom('ne_pas_repondre@unicaen.fr', "Application OSE")
            ->setSubject( sprintf(
                "[OSE %s, n°%s: Notif %s] %s",
                $this->getServiceContext()->getAnnee(),
                $notification->getIndicateur()->getNumero(),
                $notification->getFrequenceToString(),
                strip_tags($notification->getIndicateur()->getLibelle())
            ) )
            ->setBody($body)
            ->addTo($notification->getAffectation()->getPersonnel()->getEmail(), (string)$notification->getAffectation()->getPersonnel());

        return $message;
    }



    /**
     * @return Mail
     */
    private function getMail()
    {
        return $this->getServiceLocator()->get('ControllerPluginManager')->get('mail');
    }
}