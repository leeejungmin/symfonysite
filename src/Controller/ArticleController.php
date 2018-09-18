<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Forms;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Amis;
use App\Entity\Comment;

use App\Repository\PersonRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ PasswordType;
use App\Form\ ArticleRegisterType;
use App\Form\ CommentRegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ArticleController extends Controller
{


  /**
  * @Route("/article", name="article")
  */
  public function showarticletest(){



    $user = $this->getUser();

    $repo = $this->getDoctrine()->getRepository(Article::class);

    $articles = $repo->findAll();






    return $this->render('article/articletest.html.twig', [


      'articles' => $articles,

      'user' => $user,

    ]);}


    /**
    * @Route("/treattest/{id}", name="treattest")
    */
    public function treattest(Comment $comment=null,$id){

      if(!$comment){

         $comment= new Comment();
       }

        $entityManager = $this->getDoctrine()->getManager();
        $repo = $entityManager->getRepository(Article::class);

        $article = $repo->find($id);

        $author = $_POST["author"];
        $content = $_POST["content"];

        $comment->setAuthor($author)
                ->setContent($content)
                ->setArticle($article);

       $entityManager->persist($comment);
       $entityManager->flush();

       return $this->redirectToRoute('article');

     }

  //    // depuis ici c'est realite pour utiliser
  // /**
  // * @Route("/article", name="article")
  // */
  // public function showarticle(){
  //
  //
  //   $repo = $this->getDoctrine()->getRepository(Article::class);
  //
  //   $articles = $repo->findAll();
  //
  //
  //   $user = $this->getUser();
  //
  //   return $this->render('article/article.html.twig', [
  //
  //
  //     'articles' => $articles,
  //
  //     'user' => $user,
  //
  //   ]);
  // }
    /**
     * @Route("/articleedit/{id}", name="articleupdate")
     * @Route("/article/register", name="articleregister")
     */
    public function registerarticle(Article $article = null ,Request $request, ObjectManager $manager)
    {

      if(!$article){

        $article = new Article();
      }

      $user = $this->getUser();

      $article = $article->setUser($user);

      $form = $this->createForm(ArticleRegisterType::class, $article);

      $form->handleRequest($request);



      if($form->isSubmitted() && $form->isValid()){

        $manager->persist($article);
        $manager->flush();

        return $this->redirectToRoute('article');
      }
        return $this->render('article/artregister.html.twig', [

              'form' => $form->createView(),
              'user' => $user,
        ]);
    }


    /**
     * @Route("/articledelete/{id}", name="articledelete")
     */
    public function articledelete($id){



          $article = new Article();

          $em = $this->getDoctrine()->getManager();

          $post = $em->getRepository($article)->find($id);


          if($form->isSubmitted() && $form->isValid()){

            $em->remove($post);
            $em->flush();


            return $this->redirectToRoute('article');
          }

          $user = $this->getUser();
          $id = $user->getId();

          $articleuser = $article->getUser();

        return $this->render('article/article.html.twig', [


            'articles' => $article,
            'articlesuser' => $articleuser,
            'user' => $user,
            'id' => $id,

        ]);
    }
    /**
     * @Route("/commentregister/{id}", name="commentregister")
     */
    public function commentregister($id,Comment $comment = null, Article $article = null, Request $request, ObjectManager $manager)
    {
      $user = $this->getUser();


      // creer nowtime
      // $t=time();
      // $time=date("Y-m-d",$t);

      if(!$article){

        $article = new Article();
      }

      if(!$comment){

        $comment= new Comment();
      }


      $article = $this->getDoctrine()
                      ->getRepository(Article::class)
                      ->find($id);

     $comment = $comment->setArticle($article);



     // creer la form
      $form = $this->createForm(CommentRegisterType::class, $comment);

      $form->handleRequest($request);



      if($form->isSubmitted() && $form->isValid()){

        $manager->persist($comment);
        $manager->flush();

        return $this->redirectToRoute('article');
      }
        return $this->render('article/addcomment.html.twig', [

              'form' => $form->createView(),
              'user' => $user,
              'time' => $time,
        ]);
    }

}
