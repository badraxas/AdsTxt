<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

abstract class AbstractAdsTxtLine implements AdsTxtLineInterface
{
    protected array $error = [];
    protected array $notice = [];
    protected string $rawValue = '';
    protected array $warning = [];

    public function addError(string $error)
    {
        $this->error[] = $error;
    }

    public function addNotice(string $notice)
    {
        $this->notice[] = $notice;
    }

    public function addWarning(string $warning)
    {
        $this->warning[] = $warning;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getNotice()
    {
        return $this->notice;
    }

    public function getRawValue(): string
    {
        return $this->rawValue;
    }

    public function getWarning()
    {
        return $this->warning;
    }

    public function setRawValue(string $rawValue)
    {
        $this->rawValue = $rawValue;
    }
}
