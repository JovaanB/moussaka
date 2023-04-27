<?php

declare(strict_types=1);

namespace App\Artists\Infrastructure\Symfony\Model;

use App\Artists\Domain\Entity\Album as DomainAlbum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\UuidV4;

class Album
{
    private string $id;
    private ?string $name = null;
    private Artist $artist;

    /**
     * @var Collection<Song>
     */
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

    public static function fromDomain(DomainAlbum $domainAlbum): self
    {
        $artist = new self();
        $artist->id = $domainAlbum->getId();
        $artist->name = $domainAlbum->getName();
        return $artist;
    }
}
