<?php
/**
 * Created by PhpStorm.
 * User: yanni
 * Date: 8/3/2018
 * Time: 9:54 AM
 */

namespace App\Controller;
use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class MicroPostController
 * @Route("/micro-post")
 */
class MicroPostController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(\Twig_Environment $twig,
                                MicroPostRepository $microPostRepository,
                                FormFactoryInterface $formFactory,
                                EntityManagerInterface $manager,
                                RouterInterface $router,
                                FlashBagInterface $flashBag)
    {

        $this->twig = $twig;
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index(){
        $html = $this->twig->render('micro-post/index.html.twig',[
            'posts' => $this->microPostRepository->findBy([],['time' => 'DESC'])
        ]);

        return new Response($html);
    }

    /**
     * @Route("/edit/{id}",name="micro_post_edit")
     */
    public function edit(MicroPost $microPost, Request $request){
        $form = $this->formFactory->create(MicroPostType::class,$microPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $microPost->setTime(new \DateTime());
            $this->manager->flush();

            return new RedirectResponse($this->router->generate('micro_post_index'));
        }
        return new Response($this->twig->render('micro-post/add.html.twig',[
            'form'=> $form->createView()
        ]));
    }

    /**
     * @Route("/add", name="micro_post_add")
     */
    public function add(Request $request){
        $microPost = new MicroPost();
        $microPost->setTime(new \DateTime());

        $form = $this->formFactory->create(MicroPostType::class,$microPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($microPost);
            $this->manager->flush();

            return new RedirectResponse($this->router->generate('micro_post_index'));
        }

        return new Response($this->twig->render('micro-post/add.html.twig',[
            'form'=> $form->createView()
        ]));
    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     */
    public function delete(MicroPost $microPost){
        $this->manager->remove($microPost);
        $this->manager->flush();

        $this->flashBag->add('notice','Post deleted!');

        return new RedirectResponse($this->router->generate('micro_post_index'));
    }

    /**
     * @Route("/{id}", name="micro_post_post")
     */
    public function post(MicroPost $post){

        return new Response(
            $this->twig->render('micro-post/post.html.twig',['post'=>$post])
        );
    }
}