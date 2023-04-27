<?php

namespace App\Artists\Application\Message;

use App\Artists\Domain\Entity\Album as DomainAlbum;
use App\Artists\Infrastructure\Symfony\Model\Album;
use Symfony\Component\Form\FormInterface;

final class AlbumCreation
{
    public function __construct(public Album|DomainAlbum $album, public readonly FormInterface $form)
    {
    }
}