<?php

namespace AppBundle\Service;

use AppBundle\Entity\Comment;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Templating\EngineInterface;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;

/**
 * Class CommentService
 *
 * @package AppBundle\Service
 */
class CommentService
{

    private $em;
    private $list_limit;
    private $ts;
    private $translator;

    /**
     * CommentService constructor.
     * @param EntityManager $em
     * @param $list_limit
     * @param TokenStorage $ts
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManager $em,  $list_limit, TokenStorage $ts, TranslatorInterface $translator, EngineInterface $templating)
    {
        $this->em           = $em;
        $this->list_limit   = $list_limit;
        $this->ts           = $ts;
        $this->translator   = $translator;
        $this->templating   = $templating;
    }



    /**
     * Get pagination parameters
     *
     * @param array $posts Post list
     * @param $page  int current page
     *
     * @return array  Current pagination
     */
    public function getPagination(Paginator $Comments, $page)
    {

        $totalComments = $Comments->count();
        $totalDisplayed = $Comments->getIterator()->count();
        $maxPages = ceil($Comments->count() / $this->list_limit);

        return ['totalPosts' => $totalComments,
            'totalDisplayed' => $totalDisplayed,
            'current' => $page,
            'maxPages' => $maxPages
        ];
    }


    /**
     * Set comment status to ACCEPTED or REFUSED
     *
     * @param $data
     */
    public function modifyComment($data)
    {
        $comment = $this->em->getRepository('AppBundle:Comment')->findOneById($data['id']);
        if(!$comment)return;

        switch($data['action']){
            case 'authorize' :
                $comment->setStatus(Comment::ACCEPTED);
                break;
            case 'delete':
                $comment->setStatus(Comment::REFUSED);
                break;
            default:
                return;
        }
        $this->em->persist($comment);
        $this->em->flush();
    }

    /**
     * Add new comment
     *
     * @param Post $post
     * @param $message
     * @return array
     */
    public function add(Post $post, $message){

        if(!empty(trim($message))){
            $validate   = false;
            $user       = $this->ts->getToken()->getUser();

            $comment = new Comment();
            $comment->setPost($post);
            $comment->setUser($user);
            $comment->setContent($message);

            $role = $user->getRole();

            if($role == 'ROLE_ADMIN' || $role == 'ROLE_NATURALIST'){
                $comment->setStatus($comment::ACCEPTED);
                $validate = true;
            }

            $this->em->persist($comment);
            $this->em->flush();

            if($validate){
                $response = array(
                    'status'    => true,
                    'validate'  => true,
                    'message'   => $this->templating->render('common/cards/comment.html.twig', ['comment' => $comment ])
                );
            }else{
                $response = array(
                    'status'    => true,
                    'validate'  => false,
                    'message'   => $this->translator->trans('message_need_moderation')
                );
            }
        }else{
            $response = array(
                'status'    => false,
                'message'   => $this->translator->trans('require_input')
            );
        }
        return $response;
    }

    /**
     * Delete all comments for user
     *
     * @param User $user
     */
    public function deleteCommentsForUser(User $user){
        $this->em->getRepository(Comment::class)->deleteByUser($user->getId());
    }
}

