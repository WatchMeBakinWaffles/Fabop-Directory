<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailingController extends AbstractController
{
    /**
     * @Route("/mail", name="mailing")
     */
    public function index(Request $request, \Swift_Mailer $mailer,
        LoggerInterface $logger)
    {
        $mailText = $request->get('message');
        $object = $request->get('subject');
        $mails = $request->get('mailList');

        $message = new \Swift_Message($object);
        $message->setFrom($_SERVER['MAIL_USERNAME']);
        $message->setTo($mails);
        $message->setBody(
            $this->renderView(
                'mails/example.html.twig', ['subject' =>$object ,'message' => $mailText],
            ),
            'text/html'
        );

        $mailer->send($message);

        $logger->info('email sent');
        $this->addFlash('notice', 'Email sent');

        return $this->redirectToRoute('entity_people_index');
    }
}
