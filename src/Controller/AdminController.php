<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminController extends AbstractController
{
    #[Route('/insert', name: 'insert')]
    public function insert(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $produit = new Produit();
        $formProduit = $this->createForm(ProduitType::class, $produit);
        $formProduit->add('creer', SubmitType::class, [
            'label' => 'Insertion d\'un produit',
            'validation_groups' => ['registration', 'all']
        ]);

        $formProduit->handleRequest($request);

        if ($formProduit->isSubmitted() && $formProduit->isValid()) {
            /** @var UploadedFile|null $lienImageFile */
            $lienImageFile = $formProduit->get('lienImage')->getData();

            if ($lienImageFile instanceof UploadedFile) {
                $originalFilename = pathinfo(
                    $lienImageFile->getClientOriginalName(),
                    PATHINFO_FILENAME
                );
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename =
                    $safeFilename . '-' . uniqid() . '.' . $lienImageFile->guessExtension();

                try {
                    $lienImageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l’exception si besoin
                }

                $produit->setLienImage($newFilename);
            }

            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('liste');
        }

        return $this->render('admin/create.html.twig', [
            'my_form' => $formProduit->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(
        Request $request,
        $id,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $produitRepository = $entityManager->getRepository(Produit::class);
        $produit = $produitRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        // On sauvegarde l'ancienne image AVANT le handleRequest
        $oldImage = $produit->getLienImage();

        $formProduit = $this->createForm(ProduitType::class, $produit);
        $formProduit->add('creer', SubmitType::class, [
            'label' => 'Mise à jour d\'un produit',
            'validation_groups' => ['all']
        ]);

        $formProduit->handleRequest($request);

        if ($request->isMethod('post') && $formProduit->isValid()) {
            /** @var UploadedFile|null $file */
            $file = $formProduit['lienImage']->getData();

            if ($file instanceof UploadedFile) {
                $originalFilename = pathinfo(
                    $file->getClientOriginalName(),
                    PATHINFO_FILENAME
                );
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l’exception si besoin
                }

                $produit->setLienImage($newFilename);
            } else {
                // Si aucun nouveau fichier, on conserve l'ancienne image
                $produit->setLienImage($oldImage);
            }

            $entityManager->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('message', 'Le produit a été mis à jour');
            $session->set('statut', 'success');
            return $this->redirectToRoute('liste');
        }

        return $this->render('admin/create.html.twig', [
            'my_form' => $formProduit->createView()
        ]);
    }

    #[Route("/delete/{id}", name:"delete")]
    public function delete(
        Request $request,
        $id,
        EntityManagerInterface $entityManager
    ): Response {
        $produitRepository = $entityManager->getRepository(Produit::class);
        $produit = $produitRepository->find($id);

        if ($produit) {
            $entityManager->remove($produit);
            $entityManager->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('message', 'Le produit a été supprimé');
            $session->set('statut', 'success');
        }

        return $this->redirectToRoute('liste');
    }

    #[Route('/testvalid', name:'testvalid')]
    public function testAction(EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();

        $produit->setNom('');
        $produit->setPrix(20);
        $produit->setQuantite(10);
        $produit->setLienImage("monimage.jpg");
        $produit->setRupture(false);

        $validator = Validation::createValidator();
        $listErrors = $validator->validate($produit, [
            new Length(['min' => 2]),
            new NotBlank(),
        ]);

        if (count($listErrors) > 0) {
            return new Response((string) $listErrors);
        } else {
            $entityManager->persist($produit);
            $entityManager->flush();
            return new Response("ok");
        }
    }
}
