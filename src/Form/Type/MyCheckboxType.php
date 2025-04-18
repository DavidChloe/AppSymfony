<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MyCheckboxType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'cPerso'],
            'label_attr' => ['class' => 'cEtiquette'],
        ]);
    }

    public function getParent(): string
    {
        return CheckboxType::class;
    }
}
