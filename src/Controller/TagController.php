<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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

    /**
     * @Route("/tags.{_format}", name="tag_xml")
     */
    public function export(
        $_format,
        SerializerInterface $serializer,
        ProductRepository $repository
    ) {
        $products = $repository->findAll();

        $content = $serializer->serialize(
            $products,
            $_format,
            ['groups' => 'api']
        );

        $response = new Response($content);
        // $response->headers->set('Content-Disposition', 'inline');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.'.$_format.'";');

        return $response;
    }
}
