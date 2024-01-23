<?php

namespace Badraxas\Adstxt\Interfaces;

use Badraxas\Adstxt\Lines\AbstractAdsTxtLine;

interface ParserInterface
{
    public function parse(string $line): AbstractAdsTxtLine;
}
