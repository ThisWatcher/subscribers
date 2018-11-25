<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SubscriberType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name')
            ->add('email', null, [
                'disabled' => $options['is_edit']
            ])
            ->add('state', ChoiceType::class, [
                'choices' => Entity\Subscriber::$statusList]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
            [
                'data_class' => Entity\Subscriber::class,
                'csrf_protection' => false,
                'allow_extra_fields' => true,
                'is_edit' => false,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'subscriber_type';
    }
}