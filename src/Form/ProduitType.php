<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom produit :',
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix :',
            ])
            ->add('quantite', NumberType::class, [
                'label' => 'Quantité :',
            ])
            ->add('rupture', CheckboxType::class, [
                'label' => 'Rupture de stock ?',
                'required' => false,
            ])
            ->add('lienImage', FileType::class, [
                'label' => 'Image :',
                'required' => false,
                'data_class' => null,
            ])
            ->add('distributeurs', EntityType::class, [
                'class' => \App\Entity\Distributeur::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => false, // menu déroulant
                'label' => 'Distributeur(s)',
                'required' => false,
                'placeholder' => 'Choisir un ou plusieurs distributeurs',
            ])
            ->add('reference', EntityType::class, [
                'class' => \App\Entity\Reference::class,
                'choice_label' => 'numero', // Affiche le numéro dans le menu déroulant
                'required' => false,
                'placeholder' => 'Choisir une référence',
                'label' => 'Numéro de référence',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
