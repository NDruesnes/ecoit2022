<?php

namespace App\Form;

use App\Entity\Instructor;
use App\Entity\Speciality;
use App\Repository\SpecialityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationInstructorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => false,
                'attr' => ['placeholder' => 'Email']
            ])
            ->add('pseudo', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => ['placeholder' => 'Pseudo']
            ])
            ->add('specialities', EntityType::class, [
                'class' => Speciality::class,
                'choice_label' => 'speciality',
                'label' => 'Spécialité',
                'mapped' => true,
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'query_builder' => function(SpecialityRepository $specialityRepo) {
                    return $specialityRepo->createQueryBuilder('c')->orderBy('c.speciality', 'ASC');
                }
            ])
            ->add('profilimg', FileType::class,[
                'label' => false,
                'multiple' => false,
                'mapped' => false,
                'attr' => ['placeholder' => 'Photo de profil']
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
               
                'label' => false,
                'attr' => ['placeholder' => 'Mot de passe',
                            'autocomplete' => 'new-password',
                            ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Instructor::class,
        ]);
    }
}
