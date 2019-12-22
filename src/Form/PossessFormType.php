<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserSkill;
use App\Entity\Possess;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PossessFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('skills', EntityType::class, [
                'class' => UserSkill::class,
                'choice_label' => function($skill){ // function qui recupere les infos des compétences
                    return $skill->getId();
                }
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function($user){ // function qui recupere les infos de l'utilisateur
                    return $user->getId();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Possess::class,
        ]);
    }
}
