<?php

namespace Indicateur\Controller;

use Application\Service\ContextService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Indicateur\Entity\Db\Indicateur;
use Laminas\Mail\Message as MailMessage;
use Laminas\Mime\Message as MimeMessage;
use Laminas\Mime\Mime;
use Laminas\Mime\Part as MimePart;
use Laminas\View\Renderer\PhpRenderer;

/**
 * Classe dédiée à l'envoi des mails aux intervenants retournés par un indicateur.
 */
class IndicateurIntervenantsMailer
{
    use ContextServiceAwareTrait;
    use ParametresServiceAwareTrait;

    /**
     * @var IndicateurController
     */
    private $controller;

    /**
     * @var Indicateur
     */
    private $indicateur;

    /**
     * @var PhpRenderer
     */
    private $renderer;



    public function __construct(IndicateurController $controller, Indicateur $indicateur, PhpRenderer $renderer)
    {
        $this->controller = $controller;
        $this->indicateur = $indicateur;
        $this->renderer   = $renderer;
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
        $html = $data['body'];
        if (!empty($data['emailsIntervenant'])) {
            $htmlLog = "<br/><br/>------------------------------------------------ <br/><br/>";
            $htmlLog = "<p>Email envoyé au(x) destinataire(s) suivant(s) : <br/>";

            foreach ($data['emailsIntervenant'] as $email => $name) {
                $htmlLog .= $name . " / " . $email . "<br/>";
            }
            $htmlLog .= "</p>";
            $html    .= $htmlLog;
        }
        $part          = new MimePart($html);
        $part->type    = Mime::TYPE_HTML;
        $part->charset = 'UTF-8';
        $body          = new MimeMessage();
        $body->addPart($part);

        $from = (isset($data['from'])) ? $data['from'] : $this->getFrom();

        return (new MailMessage())
            ->setEncoding('UTF-8')
            ->setFrom($from, "Contact Application " . ($app = $this->controller->appInfos()->getNom()))
            ->setSubject($data['subject'])
            ->setBody($body);
    }



    public function getFrom()
    {
        /** @var ContextService $context */
        $context   = $this->controller->getServiceContext();
        $parametre = $this->getServiceParametres();

        $from = trim($parametre->get('indicateur_email_expediteur') ?? '');
        if (!empty($from)) {
            return $from;
        }

        $from = $context->getUtilisateur()->getEmail();

        return $from;
    }



    public function getDefaultSubject()
    {
        /** @var ContextService $context */
        $context = $this->controller->getServiceContext();

        $subject = sprintf("%s %s : %s",
                           $this->controller->appInfos()->getNom(),
                           $context->getAnnee(),
                           strip_tags($this->indicateur->getTypeIndicateur())
        );

        return $subject;
    }



    public function getDefaultBody()
    {
        /** @var ContextService $context */
        $context = $this->controller->getServiceContext();

        // corps au format HTML
        $html = $this->renderer->render('indicateur/indicateur/mail/intervenants', [
            'phrase'    => '',
            'signature' => $context->getUtilisateur(),
            'structure' => $context->getStructure(),
        ]);

        return $html;
    }



    public function sendCopyEmail($emailsUtilisateur, $emailsIntervenant, $data, $logs = null)
    {
        $data['emailsIntervenant'] = $emailsIntervenant;
        $message                   = $this->createMessage($data);
        $message->setSubject('COPIE | ' . $data['subject']);
        foreach ($emailsUtilisateur as $email => $name) {
            $message->setTo($email, $name);
        }
        $this->controller->mail()->send($message);
    }
}
