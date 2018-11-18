<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity;

class FieldType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('title')
            ->add('value')
            ->add('subscriber');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
            [
                'data_class' => Entity\Field::class,
                'csrf_protection' => true,
                'allow_extra_fields' => true,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'field_type';
    }
}