<?php

declare(strict_types=1);

namespace App\Artists\Infrastructure\Symfony\Model;

use App\Customers\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\UuidV4;
use App\Artists\Domain\Entity\Artist as DomainArtist;

class Artist
{
    private string $id;
    private ?string $name = null;
    private ?User $user = null;
    private Collection $albums;

    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    /**
     * @param Collection $albums
     */
    public function setAlbums(Collection $albums): void
    {
        $this->albums = $albums;
    }

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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function setSongs(Collection $songs): void
    {
        $this->songs = $songs;
    }

    public static function fromDomain(DomainArtist $domainArtist): self
    {
        $artist = new self();
        $artist->id = $domainArtist->getId();
        $artist->name = $domainArtist->getName();
        $artist->user = $domainArtist->getUser();
        $artist->albums = $domainArtist->getAlbums();
        return $artist;
    }
}
