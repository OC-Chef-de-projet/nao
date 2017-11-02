<?php

namespace AppBundle\TestsService;

use AppBundle\Service\ObservationService;
use AppBundle\Entity\Observation;
use PHPUnit\Framework\TestCase;

use OC\BookingBundle\Service\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ObservationTest extends WebTestCase
{

    protected static $translation;
    protected static $em;
   
    public static function setUpBeforeClass()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        self::$translation = $kernel->getContainer()->get('translator');
        self::$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');       
    }

    /**
     *  Get last 3 Observations
     *
     * @return [type] [description]
     */
    public function testObservations()
    {
        $ts = new TokenStorage();
        $obs = new ObservationService(self::$em, $ts, 50, './', self::$translation);
        $result = $obs->getLastObersations(2);
        $this->assertCount(2, $result);
    }
}


