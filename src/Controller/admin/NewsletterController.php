<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    /**
     * @Route("/admin/newsletters", name="admin_newsletters")
     * @param UserRepository $repo
     * @return Response
     *
     */
    public function newsletter(UserRepository $repo)
    {
        $users = $repo->findByNewsletter(true);

        if(!$users)
        {
            $this->addFlash("danger", "pas d'utilisateurs inscrits!");
            return $this->redirectToRoute('admin_home');

        }
            return $this->render('/admin/admin_newsletter.html.twig', [
                'users' => $users
            ]);
    }

    /**
     * @Route("admin/newsletter/signout", name="newsletter_signout")
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function signoutNewsletter(EntityManagerInterface $em)
    {
        $query  = explode('&', $_SERVER['QUERY_STRING']);
        $params = [];

        foreach( $query as $param )
        {
            // prevent notice on explode() if $param has no '='
            if (strpos($param, '=') === false) $param .= '=';

            list($name, $value) = explode('=', $param, 3);
            $params[urldecode($name)][] = urldecode($value);

        }

        foreach ($params as $param => $value)
        {
            for($i = 0; $i < count($value); $i++)
            {
                $id = $value[$i];

                $user = $em->getRepository(User::class)->find($id);

                if($user)
                {
                    $user->setNewsletter(false);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                }
            }
        }
                $this->addFlash("success", "Utilisateur désinscrit!");
                return $this->redirectToRoute('admin_newsletters');
    }
}
