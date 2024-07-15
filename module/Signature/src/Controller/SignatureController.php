<?php

namespace Signature\Controller;

use Application\Controller\AbstractController;
use Contrat\Service\ContratServiceAwareTrait;
use UnicaenSignature\Entity\Db\Signature;
use UnicaenSignature\Entity\Db\SignatureRecipient;
use UnicaenSignature\Service\SignatureConfigurationServiceAwareTrait;
use UnicaenSignature\Service\SignatureService;
use UnicaenSignature\Service\SignatureServiceAwareTrait;
use UnicaenVue\View\Model\VueModel;


/**
 * Description of SignatureController
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class SignatureController extends AbstractController
{

    use SignatureServiceAwareTrait;
    use SignatureConfigurationServiceAwareTrait;
    use ContratServiceAwareTrait;

    public function indexAction()
    {
        return [];
    }



    public function configurationAction()
    {
        $parapheurs = $this->getSignatureConfigurationService()->getLetterfileConfiguration();
        $logger     = $this->getSignatureConfigurationService()->getLoggerConfiguration();


        return compact('logger', 'parapheurs');
    }



    public function listeContratAction()
    {
        $vm = new VueModel();
        $vm->setTemplate('signature/liste-contrat');

        return $vm;
    }



    public function getDataContratAction()
    {
        $post = $this->axios()->fromPost();

        return $this->getServiceContrat()->getDataSignatureContrat($post);
    }



    public function updateSignatureAction()
    {
        /**
         * @var Signature $signature
         */
        $signature = $this->getEvent()->getParam('signature');
        $this->getSignatureService()->updateStatusSignature($signature);

        return false;
    }



    public function getDocumentAction()
    {

        $signature = $this->getEvent()->getParam('signature');
        $document  = $this->getSignatureService()->getDocumentSignedSignature($signature);


        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="test.pdf"');
        header('Content-Transfer-Encoding: binary');
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        echo $document;
        die;
    }

}

