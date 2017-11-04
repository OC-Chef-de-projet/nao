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
use AppBundle\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class LoadPostData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadPostData extends Fixture implements FixtureInterface, ContainerAwareInterface
{

    /**
     * Create posts
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // bin/console doctrine:fixtures:load

            $post = new Post();
            $post->setTitle('Savoir s\'équiper');
            $post->setContent('Contenu');
            $post->setStatus(Post::PUBLISHED);
            $post->setImagelink('annie-spratt-218892.jpg');
            $this->addReference('post1', $post);
            $manager->persist($post);

            $post = new Post();
            $post->setTitle('GoT : les Ravens');
            $post->setContent('Contenu');
            $post->setStatus(Post::PUBLISHED);
            $post->setImagelink('tyler-quiring-21351.jpg');
            $this->addReference('post2', $post);
            $manager->persist($post);


            $post = new Post();
            $post->setTitle('S\'orienter en foret');
            $post->setContent('Contenu');
            $post->setStatus(Post::PUBLISHED);
            $post->setImagelink('elliot-cooper-364371.jpg');
            $this->addReference('post3', $post);
            $manager->persist($post);

            $manager->flush();

        for($i = 0 ; $i < 10 ; $i++) {
            $post = new Post();
            $post->setTitle($i.' - Titre de l\'article');
            $post->setContent('Contenu');
            $post->setImagelink('elliot-cooper-364371.jpg');
            if($i %3)
            {
                $post->setStatus(Post::DRAFT);
            } else {
                $post->setStatus(Post::PUBLISHED);
            }
            $manager->persist($post);
        }
        $manager->flush();
    }
}