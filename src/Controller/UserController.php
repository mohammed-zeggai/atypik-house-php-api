<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="get_users", methods={"GET"})
     */
    public function getAll(ManagerRegistry $doctrine)
    {
        $users = $doctrine->getRepository(User::class)->findAll();

        return $this->json([
            "data" => $users,
        ]);
    }

    /**
     * @Route("/user/{id}", name="get_one_user", methods={"GET"})
     */
    public function getOne(ManagerRegistry $doctrine, string $id)
    {
        $user = $doctrine->getRepository(User::class)->findOneBy(array("id" => $id));

        return $this->json([
            "data" => $user,
        ]);
    }
    
    /**
     * @Route("/user", name="create_user", methods={"POST"})
     */
    public function create(
        ManagerRegistry $doctrine,
        Request $request,
        SerializerInterface $serializer)
    {
        $entityManager = $doctrine->getManager();
        
        $user = $serializer->deserialize($request->getContent(), User::class, "json");

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            "message" => "User created!",
            "data" => $user
        ]);
    }

    /**
     * @Route("/user/{id}", name="update_user", methods={"PUT"})
     */
    public function update(
        ManagerRegistry $doctrine,
        Request $request,
        SerializerInterface $serializer,
        int $id)
    {
        $entityManager = $doctrine->getManager();

        $user = $doctrine->getRepository(User::class)->findOneBy(array("id" => $id));
        $newUser = $serializer->deserialize($request->getContent(), User::class, "json");

        $user->setNom($newUser->getNom());
        $user->setPrenom($newUser->getPrenom());
        $user->setEmail($newUser->getEmail());
        $user->setImage($newUser->getImage());
        $user->setDateNaissance($newUser->getDateNaissance());
        $user->setTelephone($newUser->getTelephone());
        $user->setAdresse($newUser->getAdresse());
        $user->setRole($newUser->getRole());

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            "message" => "User updated!",
            "data" => $user
        ]);
    }

    /**
     * @Route("/user/{id}", name="delete_user", methods={"DELETE"})
     */
    public function delete(ManagerRegistry $doctrine, int $id)
    {
        $entityManager = $doctrine->getManager();

        $user = $doctrine->getRepository(User::class)->findOneBy(array("id" => $id));

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json([
            "message" => "User deleted!",
            "user" => $user
        ]);
    }
}
