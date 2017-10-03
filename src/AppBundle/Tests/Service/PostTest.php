<?php

namespace AppBundle\TestsService;

use AppBundle\Service\PostService;
use AppBundle\Entity\Post;
use PHPUnit\Framework\TestCase;

use OC\BookingBundle\Service\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostTest extends WebTestCase
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
     * get last post
     *
     * @return [type] [description]
     */
    public function testLastPost()
    {
        $post = new PostService(self::$em, '', 1);
        $result = $post->getLastPost();
        $this->assertInstanceOf(Post::class, $result);
    }

    /**
     *  Get last 3 posts
     *
     * @return [type] [description]
     */
    public function testLastPosts()
    {
        $post = new PostService(self::$em, '', 1);
        $result = $post->getLastPosts(3);
        $this->assertCount(3, $result);
    }
}

