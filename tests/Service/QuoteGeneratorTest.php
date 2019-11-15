<?php

namespace App\Tests\Service;

use App\Service\NameGenerator;
use App\Service\QuoteGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment as Twig;

class QuoteGeneratorTest extends KernelTestCase
{

    protected function setUp()
    {
        $kernel = self::bootKernel();
    }

    // funcional
    public function testGetQuote() {
        $container = self::$container;
        $quoteGenerator = $container->get(QuoteGenerator::class);
        $quote = $quoteGenerator->getQuote(false);
        $this->assertNotEmpty($quote);
        $this->assertTrue(strpos($quote, 'dice') !== false);
    }

    // unitario
    public function testUnitGetQuote() {

        $nameGenerator = $this->getMockBuilder(NameGenerator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $nameGenerator->expects(self::exactly(1))->method('getName')->willReturn('Sebastian');

        $swiftMailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $swiftMailer->expects(self::exactly(0))->method('send');

        $twig = $this->getMockBuilder(Twig::class)
        ->disableOriginalConstructor()
        ->getMock();
        $twig->expects(self::exactly(0))->method('render');
        
        $quoteGenerator = new QuoteGenerator($nameGenerator, $swiftMailer, $twig, 'to');

        $quote = $quoteGenerator->getQuote();
        $this->assertTrue(strpos($quote, 'Sebastian') !== false);


        $nameGenerator = $this->getMockBuilder(NameGenerator::class)
        ->disableOriginalConstructor()
        ->getMock();
        $nameGenerator->expects(self::exactly(1))->method('getName')->willReturn('Sebastian');

        $swiftMailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $swiftMailer->expects(self::exactly(1))->method('send');

        $twig = $this->getMockBuilder(Twig::class)
        ->disableOriginalConstructor()
        ->getMock();
        $twig->expects(self::exactly(1))
            ->method('render');
        
        $quoteGenerator = new QuoteGenerator($nameGenerator, $swiftMailer, $twig, 'to@mail.com');

        $quote = $quoteGenerator->getQuote(true);
        $this->assertTrue(strpos($quote, 'Sebastian') !== false);
    }
}