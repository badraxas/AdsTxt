<?php

namespace Badraxas\Adstxt\Interfaces;

interface ParserInterface
{
    public function parse(string $line): AdsTxtLineInterface;
}
