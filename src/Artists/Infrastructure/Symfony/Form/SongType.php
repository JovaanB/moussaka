<?php

namespace App\Artists\Infrastructure\Symfony\Form;

use App\Artists\Domain\Entity\Album;
use App\Artists\Domain\Entity\Artist;
use App\Artists\Domain\Entity\Song;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
            ->add('duration', null, [
                'label' => 'Durée'
            ])
            ->add('album', EntityType::class, [
                'label' => 'Album',
                'class' => Album::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $artists = array_map(fn (Artist $artist) => $artist->getId(), $options['user']->getArtists()->toArray());

                    $qb = $er->createQueryBuilder('a');
                    $qb->where($qb->expr()->in('a.artist', $artists));

                    return $qb;
                },
                'choice_label' => 'name',
            ])
            ->add('filePath', null, [
                'label' => 'URL'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Song::class,
            'user' => null,
        ]);
    }
}
