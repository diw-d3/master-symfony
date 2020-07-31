<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

class TagsArrayToStringTransformer implements DataTransformerInterface
{
    private $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * On va de [] à une string
     * ['a', 'b', 'c'] => a, b, c
     */
    public function transform($tags)
    {
        return implode(',', $tags);
    }

    /**
     * On va d'une string à []
     * a, b, c, d => [$a, $b, $c, $d]
     * a,, b, c, d => ???
     * a, a, a => ???
     * '' => ???
     */
    public function reverseTransform($value)
    {
        $names = array_filter(array_unique(
            array_map('trim', explode(',', $value))
        )); // tag1, tag2, tag3
        $tags = $this->repository->findByName($names);
        $names = array_diff($names, $tags);

        foreach ($names as $name) {
            $tags[] = (new Tag())->setName($name);
        }

        return $tags;
    }
}
