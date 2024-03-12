<?php

namespace Tests\Unit\Support;

use App\Support\Str;
use Tests\TestCase;

class StrTest extends TestCase
{
    /** @test */
    public function it_should_return_true_if_a_string_contains_all_other_string()
    {
        $haystack = 'This is a test string';
        $needles = ['string', 'test'];

        $this->assertTrue(Str::containsSome($haystack, $needles));
    }

     /** @test */
     public function it_should_return_true_if_a_string_contains_some_other_string()
     {
         $haystack = 'This is a test string';
         $needles = ['string', 'not found'];

         $this->assertTrue(Str::containsSome($haystack, $needles));
     }

    /** @test */
    public function it_should_return_false_if_a_string_contains_some_other_string()
    {
        $haystack = 'This is a test string';
        $needles = ['not', 'found'];

        $this->assertFalse(Str::containsSome($haystack, $needles));
    }
}
