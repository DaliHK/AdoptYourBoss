<?php

namespace App\Controller;
use App\Entity\JobOffer;
use App\Repository\JobOfferRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('/home/home.html.twig');
    }

    /**
     * @Route("/visitor/offers", name="visitoroffers")
     */
    public function visitorOffers(JobOfferRepository $repo)
    {
        $jobOffer = $repo->findAll();

        return $this->render('/visitor/visitor_offers.html.twig', [
            'joboffers' => $jobOffer,
        ]);
    }

    /**
     * @Route("/user/offerdetails/{id}", name="offerdetails")
     */

    public function offerDetails($id)
    {
        $em = $this->getDoctrine()->getManager();
        $offers = $em->getRepository(JobOffer::class)->find($id);
        return $this->render('/visitor/offer_details.html.twig', [
            'offer' => $offers,
            'id' => $id
        ]);
    }
}
