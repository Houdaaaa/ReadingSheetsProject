<?php

namespace App\Form;

use App\Entity\ReadingSheet;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReadingSheetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Title')
            ->add('Category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title'
            ])
            ->add('Author')
            ->add('PagesNumber')
            ->add('Editor')
            ->add('EditionDate')
            ->add('Collection')
            ->add('OriginalLanguage')
            ->add('MainCharacters')
            ->add('Summary')
            ->add('EnjoyedExtract')
            ->add('CriticalAnalysis')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReadingSheet::class,
        ]);
    }
}
