<?php

namespace App\Controller\admin;

use App\Entity\JobOffer;
use App\Entity\Recruiter;
use App\Repository\OfferRepository;
use App\Repository\RecruiterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecruiterController extends AbstractController
{
    /**
     * @route("/admin/recruiters", name="admin_recruiters")
     * @param OfferRepository $repo
     * @return Response
     */
        public function recruiters(OfferRepository $repo)
    {
        $recruiters = $repo->findAll();
      

        if(!$recruiters)
        {
            $this->addFlash("danger", "pas de recruteurs!");
            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/recruiter/admin_recruiters.html.twig', [
            'recruiters' => $recruiters
        ]);

    }

    /**
     * @route("/admin/recruiter/detail/{id}", name="admin_recruiter_detail")
     * @param RecruiterRepository $repo
     * @param $id
     * @return Response
     */
    public function recruiter(RecruiterRepository $repo, $id)
    {
        $recruiter = $repo->find($id);

        return $this->render('admin/recruiter/admin_recruiter_detail.html.twig', [
            'recruiter' => $recruiter
        ]);
    }

    /**
     * @route("/admin/recruiter/offers/{id}", name="admin_recruiter_offers")
     * @param OfferRepository $repo
     * @param $id
     * @return Response
     */
        public function recruiterOffer(OfferRepository $repo, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $joboffers = $em->getRepository(JobOffer::class)->findByRecruiter($id);

        if(!$joboffers)
        {
            $this->addFlash("danger", "Pas d'offres!");
            return $this->redirectToRoute('admin_recruiters');
        }
            return $this->render('admin/recruiter/admin_recruiter_offers.html.twig', [
                'joboffers' => $joboffers
            ]);

    }

        /**
         * @route("/admin/recruiter/delete/{id}", name="admin_recruiter_delete")
         * @param $id
         * @param $em
         * @return Response
         */
        public function recruiterDelete($id, EntityManagerInterface $em)
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
                $recruiters = $em->getRepository(Recruiter::class)->find($id);

                if($recruiters)
                {
                    $em->remove($recruiters);
                    $em->flush();
                }
            }
        }
        $this->addFlash("success", "Recruteur(s) supprimé(s)");
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/admi/recruiter/offer/delete/{id}", name="admin_recruiter_offer_delete")
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public  function offerDelete($id, EntityManagerInterface $em)
    {
        $recruiter = $em->getRepository(JobOffer::class)->find($id);
        $em->remove($recruiter);
        $em->flush();

        $this->addFlash("success", "Offre d'emploi supprimée!");
        return $this->redirectToRoute('admin_recruiters');
    }

}
