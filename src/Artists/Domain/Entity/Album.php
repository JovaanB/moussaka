<?php

declare(strict_types=1);

namespace App\Artists\Domain\Entity;

use App\Artists\Domain\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Uid\UuidV4;

#[Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[Id, Column(type: 'string')]
    private string $id;

    #[Column]
    private ?string $name = null;

    #[ManyToOne(targetEntity: Artist::class, inversedBy: 'albums')]
    private Artist $artist;

    /**
     * @var Collection<Song>
     */
    #[OneToMany(mappedBy: 'album', targetEntity: Song::class, cascade: ['persist'])]
    private Collection $songs;

    public function __construct()
    {
        $this->id = (string) (new UuidV4());
        $this->songs = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getArtist(): Artist
    {
        return $this->artist;
    }

    public function setArtist(Artist $artist): void
    {
        $this->artist = $artist;
    }

    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function setSongs(Collection $songs): void
    {
        $this->songs = $songs;
    }
}
