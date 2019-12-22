<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Cv;
use App\Entity\JobOffer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\{CvRepository, JobOfferRepository, UserRepository};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminUserController extends AbstractController
{
    private $jobOffer;

    /**
     * @route("/admin/users", name="admin_users")
     * @param UserRepository $repo
     * @return Response
     */
    public function users(UserRepository $repo)
    {
        $allusers = $repo->findAll();

        $users = [];
        foreach ($allusers as $user)
        {
            $role = $user->getRoles();
            if($role === ["ROLE_USER"])
            {
                array_push($users, $user);

            }
        }
        if(empty($users))
        {

            $this->addFlash("danger", "Pas d'utilisateurs!");
            return $this->redirectToRoute("admin_home");

        }
            return $this->render('admin/user/admin_users.html.twig', [
                'users' => $users
            ]);
    }

    /**
     * @Route("admin/user/delete", name="admin_user_delete")
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function deleteUsers(EntityManagerInterface $em)
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
                    $em->remove($user);
                    $em->flush();
                }
            }
        }
        $this->addFlash("success", "Utilisateur(s) supprimé(s)");
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @route("/admin/user/detail/{id}", name="admin_user_detail")
     * @param UserRepository $repo
     * @param $id
     * @return Response
     */
    public function selectUser(UserRepository $repo, $id)
    {
        $user = $repo->find($id);

        return $this->render('admin/user/admin_user_detail.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @route("/admin/user/cv/{id}", name="admin_user_cv")
     * @param $id
     * @param CvRepository $repo
     * @return Response
     */
    public function Cvs($id, CvRepository $repo)
    {
        $cvs = $repo->findByUser($id);
        return $this->render('admin/user/admin_user_cv.html.twig', [
            'cvs' => $cvs
        ]);
    }

    /**
     * @Route("amdin/user/delete/cv/{id}", name="admin_delete_cv")
     * @param EntityManagerInterface $em
     * @param $id
     * @return RedirectResponse
     */
    public function deleteCv(EntityManagerInterface $em, $id)
    {

        $cv = $em->getRepository(Cv::class)->find($id);

        $em->remove($cv);
        $em->flush();
        $this->addFlash("success", "CV supprimé!");
        return  $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("admin/user/applications/{id}", name="admin_user_applications")
     * @param $id
     * @return Response
     */
    public function applications($id)
    {
        $em = $this->getDoctrine()->getManager();

        $listapps = $em->getRepository(Application::class)->findByUser($id);

        $offerids = [];

        for ($i = 0; $i < count($listapps); $i++)
        {
            $offerids[$i] = $listapps[$i]->getJobOffers()->getId();
        }

        $joboffers = [];

        foreach ($offerids as $offerid) {

            $joboffer = $em->getRepository(JobOffer::class)->findById($offerid);
            array_push($joboffers, $joboffer);

        }

        return $this->render('admin/user/admin_user_applications.html.twig', [
            'joboffers' => $joboffers
        ]);

    }

    /**
     * @Route("admin/user/application/delete/{id}", name="admin_application_delete")
     * @param $id
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function deleteApp($id, EntityManagerInterface $em)
    {
        $app = $em->getRepository(JobOffer::class)->find($id);

        $em->remove($app);
        $em->flush();

        $this->addFlash("success", "Candidature supprimée !");
        return $this->redirectToRoute("admin_home");
    }
}
