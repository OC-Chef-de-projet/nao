<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Cocur\Slugify\Slugify;

/**
 * Post Entity
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 */
class Post
{

    const DRAFT = 1;
    const PUBLISHED = 2;
    const ARCHIVED = 3;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var text
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publishedAt", type="datetimetz", nullable=true)
     */
    private $publishedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetimetz")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     * @Assert\Choice(choices = {Post::PUBLISHED, Post::DRAFT, Post::ARCHIVED},strict = true)
     */
    private $status;


    /**
     * @var string
     *
     * @ORM\Column(name="imagelink", type="string", length=255, nullable=true)
     */
    private $imagelink;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->createdAt      = new \DateTime();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     *
     * @return Post
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Post
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }


    public function getStatusString(){
        return self::statusToString($this->status);
    }

    public static function statusToString($status){
        switch ($status){
            case self::DRAFT:
                return 'Brouillon';

            case self::PUBLISHED:
                return 'Publié';

            case self::ARCHIVED:
                return 'Archivé';

            default:
                return 'Erreur';
        }
    }

    /**
     * Set imagelink
     *
     * @param string $imagelink
     *
     * @return Post
     */
    public function setImagelink($imagelink)
    {
        if($imagelink !== null) {
            $this->imagelink = $imagelink;
            return $this;
        }

    }

    /**
     * Get imagelink
     *
     * @return string
     */
    public function getImagelink()
    {
        return $this->imagelink;
    }

    /**
     * Get Slug for pretty URL
     * @return string
     */
    public function getSlug(){
        $slugify = new Slugify();
        return $slugify->slugify($this->getTitle());
    }
}
