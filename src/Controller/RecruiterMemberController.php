<?php

namespace App\Controller;

use Exception;
use App\Entity\JobOffer;
use App\Form\PostAddFormType;
use App\Form\RecruiterFormType;
use App\Repository\UserRepository;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ApplicationRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RecruiterMemberController extends AbstractController
{
    /**
     * @Route("/recruiter", name="homerecruiter")
     */
    public function Home()
    {
        return $this->render('/members/recruiter/recruiter_landing.html.twig');
    }

    /**
     * @Route("/recruiter/profile", name="recruiter_landing")
     */
    public function recruiterSpace()
    {
        return $this->render('/members/recruiter/recruiter_landing.html.twig');
    }

    /**
     * @Route("/recruiter/offers", name="offers")
     * @param JobOfferRepository $repo
     * @return Response
     */
    public function offersDisplay(JobOfferRepository $repo)
    {

        $recruiter = $this->getUser();
        $id = $recruiter->getId();

        $jobOffer = $repo->findOneByRecruiter(array("id" => $id));

        return $this->render('/members/recruiter/posted_offers.html.twig', [
            'jobOffer' => $jobOffer
        ]);

    }

    /**
     * @Route("recruiter/editprofile", name="editProfile")
     * @param Request $request
     * @param UserInterface $user
     * @return RedirectResponse|Response
     */

    public function editProfile(Request $request, UserInterface $user)
    {

        //affiche le formulaire deja enregistré de l'user pour qu'il puisse le consulter ou modifier
        $form = $this->createForm(RecruiterFormType::class, $user);
        //j'envoie les informations modifié à la base de données 
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('offers');
        }
        return $this->render('/members/recruiter/recruiter_profile.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recruiter/offer/post", name="post")
     * @param ObjectManager $manager
     * @param Request $request
     * @param UserInterface $user
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function postOffer(ObjectManager $manager, Request $request, UserInterface $user)
    {
        $add = new JobOffer();

        $form = $this->createForm(PostAddFormType::class, $add);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $add->setPublicationDate(new \Datetime());
            $add->setRecruiter($user);

            $manager->persist($add);
            $manager->flush();

            $this->addFlash('success','Votre annonce a bien été publiée');
            return $this->redirectToRoute('offer_skill', [
                'id' => $add->getId()
            ]);
        }


        return $this->render('/members/recruiter/post.html.twig', [
            'form' => $form->createView(),
            'pageHeading' => 'Poster une offre d\'emploi'
        ]);
    }

    /**
     * @Route("/recruiter/edit/{id}", name="edit")
     * @param JobOfferRepository $repo
     * @param $id
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return RedirectResponse|Response
     */

    public function editOffers(JobOfferRepository $repo, $id, EntityManagerInterface $em, Request $request)

    {
        $em = $this->getDoctrine()->getManager();

        $offer = $em->getRepository(JobOffer::class)->find($id);
        $form = $this->createForm(PostAddFormType::class, $offer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($offer);
            $em->flush();
            $this->addFlash(
                'success',
                'Votre annonce a bien été modifiée'
            );
            return $this->redirectToRoute('offers');
        }
            return $this->render('/members/recruiter/edit.html.twig', [
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/recruiter/delete/{id}", name="delete")
     * @param $id
     * @return RedirectResponse
     */

    public function deleteOffers($id)
    {
        $em = $this->getDoctrine()->getManager();
        $offer = $em->getRepository(JobOffer::class)->find($id);
        $em->remove($offer);
        $em->flush();
        $this->addFlash('success', 'L\' article à bien été supprimé.');
        return $this->redirectToRoute('offers');
    }

    /**
     * @Route("/recruiter/candidates", name="candidates")
     * @param ApplicationRepository $applirepo
     * @param UserRepository $userepo
     * @return Response
     */

    public function candidates(ApplicationRepository $applirepo, UserRepository $userepo)
    {
        $applications = $applirepo->findall();
        $users = $userepo->findALl();
        $recruiterId = $this->getUser()->getId();
        return $this->render('/members/recruiter/candidates.html.twig', [
            "id" => $recruiterId,
            "candidatures" => $applications,
            "users" => $users
        ]);
    }
}
