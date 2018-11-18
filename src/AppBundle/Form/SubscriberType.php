<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SubscriberType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name')
            ->add('email')
            ->add('fields', CollectionType::class, [
            'entry_type' => FieldType::class,
            'by_reference' => false,
            'entry_options' => array('label' => false),
            'allow_add' => true,
            'allow_delete' => true,]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
            [
                'data_class' => Entity\Subscriber::class,
                'csrf_protection' => false,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'subscriber_type';
    }
}