<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,[
                'label' => false,
                'attr' => ['placeholder' => 'Username', 'class' => 'form-control']])
            
                ->add('email', EmailType::class,[
                'label' => false,
                'attr' => ['placeholder' => 'Email', 'class' => 'form-control']])
            
                ->add('plainPassword', RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => false,
                    'attr' => ['placeholder' => 'Password', 'class' => 'form-control']

                ],
                'second_options' => [
                    'label' => false,
                    'attr' => ['placeholder' => 'Confirm Password', 'class' => 'form-control'],


                ]])
            
                ->add('Register', SubmitType::class, ['attr'=> ['class' => 'btn btn-lg btn-primary btn-block']])
            ;
    }
    
    /**
     * {@inheritdoc}
     * @throws \Symfony\Component\Options\Resolver\Exeption\AccessExeption
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data class' => User::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
