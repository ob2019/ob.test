<?php

namespace Tests\Unit;

use App\Helpers\CustomErrors;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class CustomErrorsTest extends TestCase
{
    private $errors = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->errors = Config::get('custom_errors.errors');
    }

    public function testIfCustomErrorsCanBeLoaded(): void
    {
        $this->assertIsArray($this->errors);
        $this->assertNotEmpty($this->errors);
    }

    public function testIfUnknownErrorCodeWillWorkForValidatorErrorMessage(): void
    {
        $error = CustomErrors::getForValidator(1000);

        $this->assertEquals("0|{$this->errors[0]}", $error);
    }

    public function testIfKnownErrorCodeWillWorkForValidatorErrorMessage(): void
    {
        $error = CustomErrors::getForValidator(1);

        $this->assertEquals("1|{$this->errors[1]}", $error);
    }

    public function testIfUnknownErrorCodeWillWorkForJsonTestingArray(): void
    {
        $error = CustomErrors::getForJsonTesting(1000);

        $this->assertEquals([
            "code" => "0",
            "message" => $this->errors[0]
            ], $error);
    }

    public function testIfKnownErrorCodeWillWorkForJsonTestingArray(): void
    {
        $errorId = 1;
        $error = CustomErrors::getForJsonTesting($errorId);

        $this->assertEquals([
            "code" => "{$errorId}",
            "message" => $this->errors[$errorId]
        ], $error);
    }

    public function testIfKnownErrorCodeWithReplacerWillWorkForJsonTestingArray(): void
    {
        // set up
        $errorId = 2;
        $search = ":min";
        $replace = "10";

        // get original results
        $error = CustomErrors::getForJsonTesting($errorId, $search, $replace);

        // prepare expected results
        $expectedError = [
            "code" => "{$errorId}",
            "message" => $this->errors[$errorId]
        ];
        $expectedError['message'] = str_replace($search, $replace, $expectedError['message']);

        // assert
        $this->assertEquals($expectedError, $error);
    }

    public function testIfErrorPayloadWorksWithCorrectInput(): void
    {
        $errorId = 1;
        $error = CustomErrors::getForValidator($errorId);

        $errorPayload = CustomErrors::getErrorPayload($error);

        $this->assertEquals([
            "code" => "{$errorId}",
            "message" => $this->errors[$errorId]
        ], $errorPayload);
    }

    public function testIfErrorPayloadWorksWithIncorrectInput(): void
    {
        $errorPayload = CustomErrors::getErrorPayload("Some unknown string");

        $this->assertEquals([
            "code" => "0",
            "message" => $this->errors[0]
        ], $errorPayload);
    }
}
