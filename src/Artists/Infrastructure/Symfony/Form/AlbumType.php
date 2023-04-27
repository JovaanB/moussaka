<?php

namespace App\Artists\Infrastructure\Symfony\Form;

use App\Artists\Infrastructure\Symfony\Model\Album;
use App\Artists\Infrastructure\Symfony\Model\Artist;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];

        foreach ($options['artists'] as $artist) {
            array_push($choices, Artist::fromDomain($artist));
        }

        $builder
            ->add('name', null, [
                'label' => 'Nom',
                'required' => true,
                'help' => 'Exemple : Nirvana- Nevermind'
            ])
        ->add('artist', ChoiceType::class, [
        'label' => 'Artiste',
        'choices' => $choices,
        'choice_label' => 'name',
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
            'artists' => [],
        ]);
    }
}
