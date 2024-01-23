<?php

namespace Badraxas\Adstxt\Lines;

use Badraxas\Adstxt\Interfaces\AdsTxtLineInterface;

abstract class AbstractAdsTxtLine implements AdsTxtLineInterface
{
    protected string $rawValue = '';
    protected array $notice = [];
    protected array $warning = [];
    protected array $error = [];

    public function getWarning()
    {
        return $this->warning;
    }

    public function addWarning(string $warning)
    {
        $this->warning[] = $warning;
    }

    public function getNotice()
    {
        return $this->notice;
    }

    public function addNotice(string $notice)
    {
        $this->notice[] = $notice;
    }

    public function getError()
    {
        return $this->error;
    }

    public function addError(string $error)
    {
        $this->error[] = $error;
    }

    public function setRawValue(string $rawValue)
    {
        $this->rawValue = $rawValue;
    }

    public function getRawValue(): string
    {
        return $this->rawValue;
    }
}