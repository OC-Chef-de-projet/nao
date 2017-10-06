<?php
/**
 * Created by PhpStorm.
 * User: Pierre-Sylvain
 * Date: 30-07-17
 * Time: 22:04
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class LoadCommentData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadCommentData extends Fixture implements FixtureInterface, ContainerAwareInterface
{

    /**
     * Create comments
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // bin/console doctrine:fixtures:load

        $cmt = new Comment();
        $cmt->setAuthor('ME');
        $cmt->setStatus(Comment::WAITING);
        $cmt->setContent('My comment');
        $cmt->setEmail('methefirst@nowhere.com');
        $cmt->setPost($this->getReference('post1'));
        $manager->persist($cmt);

        $cmt = new Comment();
        $cmt->setAuthor('ME AGAIN');
        $cmt->setStatus(Comment::WAITING);
        $cmt->setContent('My second comment');
        $cmt->setEmail('meagain@nowhere.com');
        $cmt->setPost($this->getReference('post1'));
        $manager->persist($cmt);

        $cmt = new Comment();
        $cmt->setAuthor('ME THE THIRD');
        $cmt->setStatus(Comment::WAITING);
        $cmt->setContent('My third comment');
        $cmt->setEmail('meathethird@nowhere.com');
        $cmt->setPost($this->getReference('post2'));
        $manager->persist($cmt);

        $cmt = new Comment();
        $cmt->setAuthor('ME THE LAST');
        $cmt->setStatus(Comment::WAITING);
        $cmt->setContent('My last comment');
        $cmt->setEmail('methelast@nowhere.com');
        $cmt->setPost($this->getReference('post3'));
        $manager->persist($cmt);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LoadPostData::class
        );
    }
}