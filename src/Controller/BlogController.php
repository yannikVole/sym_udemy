<?php
/**
 * Created by PhpStorm.
 * User: yanni
 * Date: 7/31/2018
 * Time: 5:26 PM
 */

namespace App\Controller;


use App\Service\Greeting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    private $twig;
    private $session;
    private $router;

    public function __construct(\Twig_Environment $twig, SessionInterface $session, RouterInterface $router)
    {
        $this->twig = $twig;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @Route("/", name="blog_index")
     */
    public function index(){
        try {
            $html = $this->twig->render(
                'blog/index.html.twig',
                [
                    'posts' => $this->session->get('posts')
                ]
            );
        } catch (\Twig_Error_Loader $e) {
            die($e->getMessage());
        } catch (\Twig_Error_Runtime $e) {
            die($e->getMessage());
        } catch (\Twig_Error_Syntax $e) {
            die($e->getMessage());
        }

        return new Response($html);
    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add(){
        $posts = $this->session->get('posts');
        $posts[uniqid()] = [
            'title' => 'A random title '.rand(1,500),
            'text' => 'Some random text nr '.rand(1,500),
            'date' => new \DateTime()
        ];
        $this->session->set('posts',$posts);
        return new RedirectResponse($this->router->generate('blog_index'));
    }

    /**
     * @Route("/show/{id}", name="blog_show")
     */
    public function show($id){
        $posts = $this->session->get('posts');
        if(!$posts || !isset($posts[$id])){
            throw new NotFoundHttpException('Post not found');
        }

        try {
            $html = $this->twig->render(
                'blog/post.html.twig',
                [
                    'id' => $id,
                    'post' => $posts[$id]
                ]
            );
        } catch (\Twig_Error_Loader $e) {
            die($e->getMessage());
        } catch (\Twig_Error_Runtime $e) {
            die($e->getMessage());
        } catch (\Twig_Error_Syntax $e) {
            die($e->getMessage());
        }

        return new Response($html);
    }
}