<?php

namespace Badraxas\Adstxt\Parsers;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;
use Badraxas\Adstxt\Interfaces\ParserInterface;
use Badraxas\Adstxt\Lines\Comment;

class CommentParser implements ParserInterface
{

    public function parse(string $line): AdsTxtLineInterface
    {
        return new Comment(rtrim(mb_strcut($line, 1)));
    }
}