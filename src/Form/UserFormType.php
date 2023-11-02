<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    protected ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'email'])
            ->add('roles', ChoiceType::class, [
                'choices' => $this->getAndFormatRolesForForm(),
                'multiple' => true,
                'required' => true,
                'label' => 'roles'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => empty($options['data']?->getPassword()),
                'first_options'  => [
                    'label' => empty($options['data']?->getPassword()) ? 'password': 'password_if_you_want_to_change'
                ],
                'second_options' => [
                    'label' => empty($options['data']?->getPassword()) ? 'repeat_password': 'repeat_password_if_you_want_to_change'
                ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    protected function getAndFormatRolesForForm(): array
    {
        $roles = $this->parameterBag->get('roles');

        $rolesForForm = [];
        foreach ($roles as $role) {
            $rolesForForm[mb_strtolower($role)] = $role;
        }

        return $rolesForForm;
    }
}
