<?php

namespace App\Tests;

use App\Slugify;
use PHPUnit\Framework\TestCase;

class SlugifyTest extends TestCase
{
    public function testCanSlugifyStrings()
    {
        $this->assertSame('hello-toto', Slugify::slugify('Hello Toto'));
    }

    public function testCanTrimStrings()
    {
        $this->assertSame('hello-toto', Slugify::slugify('   Hello   Toto   '));
    }

    public function testCanSecureStrings()
    {
        $this->assertSame('hello-toto', Slugify::slugify('<script>Hello Toto</script>'));
    }
}
