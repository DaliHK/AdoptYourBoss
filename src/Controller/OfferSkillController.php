<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Entity\OfferSkill;
use App\Form\OfferSkillFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class OfferSkillController extends AbstractController
{
    /**
     * @Route("/offer/skill/{id}", name="offer_skill")
     * @param $id
     * @param Request $request
     * @param UserInterface $user
     * @return Response
     */
    public function addSkill($id, Request $request, UserInterface $user)
    {
        $lastskills = $this->getDoctrine()->getRepository(OfferSkill::class)->findByJoboffer($id);
        $skill = new OfferSkill();

        $form = $this->createForm(OfferSkillFormType::class, $skill);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $skill->setRecruiter($user);
            $listoffers = $this->getDoctrine()->getRepository(JobOffer::class)->findByRecruiter($user);

            foreach ($listoffers as $offer)
            {
                $skill->setJobOffer($offer);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($skill);
            $entityManager->flush();

            $this->addFlash('success', 'competence ajoutée!');
            return $this->redirectToRoute('offer_skill', ['id' => $id]);
        }
            return $this->render('/members/recruiter/offer_skill.html.twig', [
                'form' => $form->createView(),
                'mainNavRegistration' => true,
                'title' => 'Inscription',
                'lastskills' => $lastskills
            ]);
    }
}
