<?php

namespace Badraxas\Adstxt\Parsers;

use Badraxas\Adstxt\Interfaces\ParserInterface;
use Badraxas\Adstxt\Lines\AbstractAdsTxtLine;
use Badraxas\Adstxt\Lines\Comment;

class CommentParser implements ParserInterface
{
    public function parse(string $line): AbstractAdsTxtLine
    {
        $raw = $line;
        $line = trim(mb_strcut($line, 1));
        $comment = new Comment($line);
        $comment->setRawValue($raw);

        if (!$this->validateComment($line)) {
            $comment->addNotice('Comment contains unusual characters/symbols, consider removing for compatibility.');
        }

        return $comment;
    }

    private function validateComment($comment): bool
    {
        return mb_check_encoding($comment, 'ASCII');
    }
}
