<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Entity\Panier;
use App\Form\PanierType;

class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager(); 
        $produit = new Produit();	
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $fichier = $form->get('photo')->getData();
            if($fichier){
                $nomfichier = uniqid() .'.'. $fichier->guessExtension();
                try{
                    $fichier->move(
                        $this->getParameter('upload_dir'),
                        $nomfichier
                    );
                }
                catch(FileException $e){
                    return $this->redirectToRoute('produit');
                }
                $produit->SetPhoto($nomfichier);
            }
        $em->persist($produit);
        $em->flush();
        $this->addFlash('success', "message.ajouter_produit_vrai");
        }
        $produits = $em->getRepository(Produit::class)->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'form_ajout' => $form->createView()
        ]);	
    }

    /**
     * @Route("/fiche_produit/{id}", name="fiche_produit")
     */
    public function produit(Produit $produit=null, Request $request){
        $em = $this->getDoctrine()->getManager(); 
        if($produit != null){
            $panier = new Panier();
            $produit->addPanier($panier);
            $form = $this->createForm(PanierType::class, $panier);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em->persist($panier);
                $em->flush();
                $this->addFlash('success', 'message.ajouter_produit_panier_vrai');
                return $this->redirectToRoute('panier');
            }

            $produits = $em->getRepository(Produit::class)->findAll();
            return $this->render('produit/fiche_produit.html.twig', [
                'produit' => $produit,
                'form_ajout' => $form->createView()
            ]);
        }
        else{
            $this->addFlash('danger', 'message.erreur');
            return $this->redirectToRoute('produit');
        }
    }

    /**
     * @Route("/produit/delete/{id}", name="delete_produit")
     */
    public function delete(Produit $produit=null){
        if ($produit != null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
            if ($produit->getPhoto() != null){
                unlink($this->getParameter('upload_dir').$produit->getPhoto());
            }
            $this->addFlash('success', 'message.supprimer_produit_vrai');
        }
        else{
            $this->addFlash('danger', 'message.erreur');
        }
    return $this->redirectToRoute('produit');
    }
}
