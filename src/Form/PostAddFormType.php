<?php

namespace App\Form;

use DateTimeInterface;
use App\Entity\JobOffer;
use App\Entity\Recruiter;
use App\Entity\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostAddFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('label' => 'Intitulé de l\'offre'))
            ->add('description', TextareaType::class, array('label' => 'Description de l\'offre'))
            ->add('start_date', DateType::class, array ('label' => 'Date de début'))
            ->add('contract', TextType::class, array('label' => 'Type de contrat'))
            ->add('end_date', DateType::class, array('label' => 'Date de fin'))
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer', 'attr' => ['class' => 'btn-info']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobOffer::class,
        ]);
    }
}
