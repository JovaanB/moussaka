<?php

declare(strict_types=1);

namespace App\Artists\Domain\Entity;

use App\Customers\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Uid\UuidV4;

#[Entity]
class Artist
{
    #[Id, Column(type: 'string')]
    private string $id;

    #[Column]
    private ?string $name = null;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'artists')]
    private ?User $user = null;

    #[OneToMany(mappedBy: 'artist', targetEntity: Album::class, cascade: ['persist'])]
    private Collection $albums;

    /**
     * @return Collection
     */
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
}
