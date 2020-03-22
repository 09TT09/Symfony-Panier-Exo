<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Panier;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager(); 
        $panier = new Panier();
        $paniers = $em->getRepository(Panier::class)->findAll();
        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers
        ]);
    }

    /**
     * @Route("/panier/delete/{id}", name="delete_panier_prod")
     */
    public function delete(Panier $panier=null){
        if ($panier != null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($panier);
            $em->flush();
            $this->addFlash('success', 'message.supprimer_produit_panier_vrai');
        }
        else{
            $this->addFlash('danger', 'message.erreur');
        }
    return $this->redirectToRoute('panier');
    }
}
