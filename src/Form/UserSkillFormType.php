<?php

namespace App\Form;

use App\Entity\UserSkill;
use App\Entity\OfferSkill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserSkillFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('skill', TextType::class, ['label' => 'Entrez une compétence à la fois'])
            ->add('submit', SubmitType::class, ['label' => 'Ajouter', 'attr' => ['class' => 'btn-info']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserSkill::class,
        ]);
    }
}
