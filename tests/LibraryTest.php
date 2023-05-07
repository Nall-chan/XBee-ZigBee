<?php

declare(strict_types=1);

include_once __DIR__ . '/stubs/Validator.php';

class LibraryTest extends TestCaseSymconValidation
{
    public function testValidateLibrary(): void
    {
        $this->validateLibrary(__DIR__ . '/..');
    }

    public function testValidateXBeeZBDevice(): void
    {
        $this->validateModule(__DIR__ . '/../XBeeZBDevice');
    }

    public function testValidateXBeeZBGateway(): void
    {
        $this->validateModule(__DIR__ . '/../XBeeZBGateway');
    }

    public function testValidateXBeeZBSplitter(): void
    {
        $this->validateModule(__DIR__ . '/../XBeeZBSplitter');
    }
}