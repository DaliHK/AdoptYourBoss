<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Entity\Possess;
use App\Entity\JobOffer;
use App\Form\CvFormType;
use App\Entity\UserSkill;
use App\Entity\OfferSkill;
use App\Form\UserFormType;
use App\Entity\Application;
use App\Form\SkillFormType;
use App\Form\ApplicationType;
use App\Form\PossessFormType;
use App\Form\UserSkillFormType;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\SkillRepository;
use Negotiation\Exception\Exception;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UserMemberController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     * @param UserRepository $repo
     * @param UserInterface $user
     * @return Response
     */
    public function memberSpace(UserRepository $repo, UserInterface $user)
    {
        $id = $user->getId();

        return $this->render('/members/user/user_landing.html.twig', [
            'id' => $id
        ]);
    }

    /**
     * @Route("user/submitapplication/{id}", name="apply")
     */

    public function submitApplication(Request $request, UserInterface $user, $id)
    {
        $application = new Application();

        $form = $this->createForm(ApplicationType::class, $application);
        $idUser = $user->getId();
        $job = $this->getDoctrine()->getRepository(JobOffer::class)->find($id);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $application->setUser($user);
            $application->setJobOffer($job);
            $em->persist($application);
            //envoie de la requete
            $em->flush();
            $this->addFlash('info', 'Votre candidature a été envoyée avec succès');
            return $this->redirectToRoute('visitoroffers');
        }

        return $this->render('/members/user/application.html.twig', [
            'form' => $form->createView(),
            'offer' => $job
        ]);
    }

    /**
     * @Route("/user/CV/{id}", name="depot_cv")
     * @param Request $request
     * @param $id
     * @param UserInterface $user
     * @return Response
     */

    public function depotCV(Request $request, $id, UserInterface $user)
    {
        $cv = new Cv();
        //je recupere mon cv dans ma class CvFormType que je lie à ma variable $cv
        $formcv = $this->createForm(CvFormType::class, $cv);

        $formcv->handleRequest($request);
        //je soumet le formulaire
        if ($formcv->isSubmitted() && $formcv->isValid()) {
            /** @var UploadedFile $cvfile */
            $cvfile = $formcv['file']->getData();

            //le fichier PDF ne doit être traité que lorsqu'un fichier est chargé
            if ($cv) {

                $originalFilename = pathinfo($cvfile->getClientOriginalName(), PATHINFO_FILENAME);
                //enleve les caratères apres le vrai nom du fichier
                //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                //cela est nécessaire pour inclure en toute sécurité le nom de fichier dans l'URL
                $newFilename = $originalFilename . '-' . md5(uniqid()) . '.' . $cvfile->guessExtension();

                //Déplace le fichier dans le répertoire où sont stockées les cv
                try {
                    $cvfile->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }
                //met à jour la propriété 'cvFilename' pour stocker le nom du fichier PDF
                $cv->setFile($newFilename);

                //au moment ou j'envoie le cv je lui envoie l'id du User qui correspond au cv téléchargé
                $cv->setUser($user);
                dump($cv);die;
                $em = $this->getDoctrine()->getManager();
                $em->persist($cv);
                //envoie de la requete
                $em->flush();
            }
            $this->addFlash('info', 'added successfully');
            //le deuxieme parametre me permet de le retourner à la skill de l'utilisateur connecté
            //return $this->redirectToRoute('depot_cv',['id' => $id]);
        }    
        
        return $this->render('/members/user/get_cv.html.twig', [
            'formcv' => $formcv->createView(),
            'id' => $id,
            
        ]);
    }

    /**
     * @Route("/user/profil/{id}/edit", name="user_profil")
     * @param UserInterface $user
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     */

    public function userProfil(UserInterface $user, Request $request, $id)
    {

        //affiche le formulaire deja enregistré de l'user pour qu'il puisse le consulter ou modifier
        $form = $this->createForm(UserFormType::class, $user);

        //j'envoie les informations modifié à la base de données
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('info', 'Mis à jour');
            return $this->redirectToRoute('user');
            
        }
        return $this->render('/members/user/user_profil.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'id' => $id
        ]);
    }

    /**
     * @Route("/user/profil/{id}/skill", name="user_skill")
     * @param Request $request
     * @param UserInterface $user
     * @return Response
     */

    public function userSkill($id, Request $request, UserInterface $user)
    {
        $skill = new UserSkill();
        //je recupere mon formulaire dans ma class SkillFormType que je lie à ma variable $skill
        $formskill = $this->createForm(UserSkillFormType::class, $skill);

        //je soumet le formulaire
        $formskill->handleRequest($request);
        
        if ($formskill->isSubmitted() && $formskill->isValid()) {
            $em = $this->getDoctrine()->getManager(); 
            
            //avant l'envoie de la requete j'envoie aussi les informations de l'utilsateur et des compétences
            $skill->setUser($user);
            $em->persist($skill);
            $em->flush();          
            $user = $skill->getUser();  
            
            //dump($lastskills);die;
            $this->addFlash('info', 'added successfully');
            //le deuxieme parametre me permet de le retourner à la skill de l'utilisateur connecté
            return $this->redirectToRoute('user_skill', ['id' => $user->getId()]);
        }
            $lastskills = $this->getDoctrine()->getRepository(UserSkill::class)->findByUser($user);

        return $this->render('/members/user/user_skill.html.twig', [
            'formskill' => $formskill->createView(),
            'id' => $id,
            'lastskills' => $lastskills
        ]);
    }

    /**
     * @Route("/user/profil/{id}/match", name="user_match")
     * @param $id
     * @param Request $request
     * @param UserInterface $user
     * @return Response
     */

    public function userMatch(Request $request, UserInterface $user, $id)
    {
        //recupere les competences qui correspond au user connecté
        $skill = $this->getDoctrine()->getRepository(UserSkill::class)->findAll();

        //stockage des compétences de l'utilisateur
        $skilluser = [];
        for ($j = 0; $j < count($skill); $j++) {
            $skilluser[$j] = $skill[$j]->getSkill();
        }
        
        // recuperer les deux classes nécessaires au match 
        $joboffer = $this->getDoctrine()->getRepository(JobOffer::class)->findAll();
        $offerskill = $this->getDoctrine()->getRepository(OfferSkill::class)->findAll();

        //stockage des compétences de l'offre d'emploi
        $skilljoboffer = [];
        for ($i = 0; $i < count($offerskill); $i++) {
            $skilljoboffer[$i] = $offerskill[$i]->getSkill();
        }

        //compare les compétences entre l'offre d'emploi et les compétences du candidat
        $result = array_intersect($skilljoboffer, $skilluser);
        
        $offers = $this->getDoctrine()->getRepository(OfferSkill::class)->findBySkill($result);
        
        $matchlist = [];
        for($z = 0 ; $z < count($offers) ; $z++){
            $matchlist[$z] = $offers[$z]->getJobOffer();
        }
        
        
        // affiche les offres d'emploi qui match
        $matchoffer = $this->getDoctrine()->getRepository(JobOffer::class)->findById($matchlist);
        //dump($matchoffer);die;
        $offerlength = count($matchoffer); 

        if (!$matchoffer) {
            $this->addFlash('danger', 'pas de match');
            return $this->redirectToRoute('home');
        }

        return $this->render('/members/user/job_match.html.twig', [
            'id' => $id,
            'matchoffer' => $matchoffer,
            'offerlength' => $offerlength
        ]);
    }
}
