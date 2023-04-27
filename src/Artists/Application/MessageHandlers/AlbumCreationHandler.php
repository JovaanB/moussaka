<?php

declare(strict_types=1);

namespace App\Artists\Application\MessageHandlers;

use App\Artists\Application\Message\AlbumCreation;
use App\Artists\Domain\Entity\Album;
use App\Artists\Domain\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(priority: 256)]
class AlbumCreationHandler
{
    public function __construct(private EntityManagerInterface $entityManager, private ArtistRepository $artistRepository)
    {
    }

    public function __invoke(AlbumCreation $albumCreation)
    {
        $album = new Album();
        $album->setName($albumCreation->album->getName());
        $album->setArtist($this->artistRepository->find($albumCreation->album->getArtist()->getId()));

        $this->entityManager->persist($album);
        $this->entityManager->flush();

    }
}
