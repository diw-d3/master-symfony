<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tag.json", name="tag_json")
     */
    public function index(TagRepository $repository)
    {
        $tags = $repository->findAll();

        return $this->json($tags, 200, [], ['groups' => 'api']);
        // return new Response(json_encode($tags));
    }
}
