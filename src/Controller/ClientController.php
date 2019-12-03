<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @Route("/", name="clients")
     */
    public function index()
    {
        $repository=$this->getDoctrine()->getRepository(Client::class);
        $client= $repository->findAll();
        return $this->render('clients/index.html.twig', [
            "clients"=>$client,
        ]);
    }

    /**
     * @Route("/client/ajouter", name="ajouter_client")
     */
    public function ajouter(Request $request)
    {
        $client=new Client();
   

        //creation du formmulaire
        $formulaire=$this->createForm(ClientType::class, $client);
 
        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid())
        {
            //récupérer l'entity manager (sorte de connexion à la BDD comme new PDO)
            $em=$this->getDoctrine()->getManager();

            //Je dis au manager que je veux ajouter la categori dans la BDD
            $em->persist($client);

            $em->flush();

            return $this->redirectToRoute("clients");
        }

        return $this->render('clients/formulaire.html.twig', [
            "formulaire"=>$formulaire->createView(),
            "h1"=>"Ajouter un client",
        ]);
    }

  /**
   * @Route("/client/supprimer/{id}", name="supprimer_client")
   */
    public function supprimer($id, Request $request)
  {
    $repository = $this->getDoctrine()->getRepository(Client::class);
    $client=$repository->find($id);

    $formulaire=$this->createForm(ClientType::class, $client);

    //récupérer l'entity manager (sorte de connexion à la BDD)
    $em = $this->getDoctrine()->getManager();

    //Je dis au manager que je veux supprimer la catégorie dans la BDD
    $em->remove($client);

    $em->flush();

    return $this->redirectToRoute("clients");
  }


    
    /**
     * @Route("/client/modifier/{id}", name="modifier_client")
     */
    public function modifier($id, Request $request)
    {
        $repository=$this->getDoctrine()->getRepository(Client::class);
        $client=$repository->find($id);

        //creation du formmulaire
        $formulaire=$this->createForm(ClientType::class, $client);

        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid())
        {
            //récupérer l'entity manager (sorte de connexion à la BDD comme new PDO)
            $em=$this->getDoctrine()->getManager();

            //Je dis au manager que je veux ajouter la categori dans la BDD
            $em->persist($client);

            $em->flush();

            return $this->redirectToRoute("clients");
        }

        return $this->render('clients/formulaire.html.twig', [
            "formulaire"=>$formulaire->createView(),
            "h1"=>"Modification d'un client <b>",$client->Getsociete().$client->Getnom().$client->Getprenom().$client->Getemail()."</b>",
        ]);
    }

   
}


