<?php

namespace App\Tests\Form\DataTransformer;

use App\Entity\Tag;
use App\Form\DataTransformer\TagsArrayToStringTransformer;
use App\Repository\TagRepository;
use PHPUnit\Framework\TestCase;

class TagsArrayToStringTransformerTest extends TestCase
{
    public function testTransform()
    {
        $transformed = $this->getMockTransformer()->transform([
            (new Tag())->setName('Tag 1'),
            (new Tag())->setName('Tag 2'),
        ]);

        $this->assertSame('Tag 1,Tag 2', $transformed);
    }

    public function testReverseTransform()
    {
        $tags = $this->getMockTransformer()->reverseTransform('  Tag 1,  Tag 2,  Tag 3 ');

        $this->assertCount(3, $tags);
        $this->assertSame('Tag 1', $tags[0]->getName());
        $this->assertSame('Tag 2', $tags[1]->getName());
        $this->assertSame('Tag 3', $tags[2]->getName());
    }

    public function testReverseTransformWithExistingTags()
    {
        // On a Tag 1 et Tag 2 en BDD
        $existingTags = [
            (new Tag())->setName('Tag 1'), (new Tag())->setName('Tag 2'),
        ];
        // On ajoute Tag 1, Tag 2, hello, notexists
        $tags = $this->getMockTransformer($existingTags)->reverseTransform('Tag 1, Tag 2, hello, notexists');
        // On ne doit créer que hello et notexists
        $this->assertCount(4, $tags);
        $this->assertSame($existingTags[0], $tags[0]);
        $this->assertSame($existingTags[1], $tags[1]);
    }

    public function testReverseCountWithComma()
    {
        $transformer = $this->getMockTransformer();

        $this->assertCount(3, $transformer->reverseTransform('Tag 1, Tag 2,, Tag 3'));
        $this->assertCount(3, $transformer->reverseTransform(',,,,Tag 1, Tag 2, Tag 3,'));
    }

    public function testDuplicateTags()
    {
        $this->assertCount(1, $this->getMockTransformer()->reverseTransform('Tag 1, Tag 1, Tag 1'));
    }

    private function getMockTransformer(
        array $tags = []
    ): TagsArrayToStringTransformer {
        $tagRepository = $this->getMockBuilder(TagRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findByName'])
            ->getMock();
        $tagRepository->method('findByName')
            ->willReturn($tags);

        return new TagsArrayToStringTransformer($tagRepository);
    }
}
