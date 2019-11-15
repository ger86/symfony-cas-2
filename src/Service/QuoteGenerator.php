<?php

namespace App\Service;

use Twig\Environment as Twig;

class QuoteGenerator
{
    private $nameGenerator;
    private $mailer;
    private $twig;
    private $mailFrom;

    public function __construct(
        NameGenerator $nameGenerator, 
        \Swift_Mailer $mailer,
        Twig $twig,
        string $mailFrom
    ) {
        $this->nameGenerator = $nameGenerator;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailFrom = $mailFrom;
    }

    public function getQuote(bool $withEmail = false) {
        $quotes = ['frase 1', 'frase 2', 'frase 3'];
        $quote = $quotes[array_rand($quotes)];
        if ($withEmail) {
            $mailContent = $this->twig->render('mail/quote.html.twig', ['quote' => $quote]);
            $message = (new \Swift_Message('Asunto del email'))
                ->setFrom($this->mailFrom)
                ->setTo('to@mail.com')
                ->setBody($mailContent, 'text/html');
            $this->mailer->send($message);
        }
        return sprintf('%s dice %s', 
            $this->nameGenerator->getName(), $quote);
    }
}