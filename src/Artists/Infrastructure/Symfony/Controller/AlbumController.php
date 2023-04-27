<?php

namespace App\Artists\Infrastructure\Symfony\Controller;

use App\Artists\Application\Message\AlbumCreation;
use App\Artists\Domain\Repository\AlbumRepository;
use App\Artists\Infrastructure\Symfony\Form\AlbumType;
use App\Artists\Infrastructure\Symfony\Model\Album;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/album')]
class AlbumController extends AbstractController
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }
    #[Route('/', name: 'app_album_index', methods: ['GET'])]
    public function index(AlbumRepository $albumRepository): Response
    {
        $artists = $this->getUser()->getArtists();
        return $this->render('album/index.html.twig', [
            'albums' => $albumRepository->findBy(
                ['artist' => $artists[0]]
            ),
        ]);
    }

    #[Route('/new', name: 'app_album_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ARTIST')]
    public function new(Request $request, AlbumRepository $albumRepository): Response
    {
        $album = new Album();
        $artists = $this->getUser()->getArtists();
        $options = [
            'artists' => $artists
        ];

        $form = $this->createForm(AlbumType::class, $album, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enveloppe = $this->messageBus->dispatch(new AlbumCreation($album, $form));
            $lastStamp = $enveloppe->last(HandledStamp::class);
            $album = $lastStamp->getResult();

            if($album) {
                return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('album/new.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_album_show', methods: ['GET'])]
    public function show(Album $album): Response
    {
        return $this->render('album/show.html.twig', [
            'album' => $album,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_album_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ARTIST')]
    public function edit(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        $artists = $this->getUser()->getArtists();
        $options = [
            'artists' => $artists
        ];

        $form = $this->createForm(AlbumType::class, $album, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumRepository->save($album, true);

            return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('album/edit.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_album_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ARTIST')]
    public function delete(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $albumRepository->remove($album, true);
        }

        return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
    }
}
