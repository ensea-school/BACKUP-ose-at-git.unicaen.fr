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
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

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


    private function createMessage($data, $emails)
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


        $from = (isset($data['from'])) ? $data['from'] : $this->getFrom();

        $email = new Email();
        $email->from(new Address($from,"Contact Application " . ($app = $this->controller->appInfos()->getNom())))
              ->subject($data['subject'])
              ->html($html);

        foreach ($emails as $value => $name) {
            $email->addTo($value, $name);
        }

        return $email;
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
        $email                   = $this->createMessage($data);
        $email->subject('COPIE | ' . $data['subject']);
        foreach ($emailsUtilisateur as $email => $name) {
            $email->to($email, $name);
        }
        $this->controller->mail()->send($message);
    }
}
