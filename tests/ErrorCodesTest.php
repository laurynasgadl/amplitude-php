<?php

namespace Luur\Amplitude\Tests;

use Luur\Amplitude\ErrorCodes;
use PHPUnit\Framework\TestCase;

class ErrorCodesTest extends TestCase
{
    public function getTextTestDataProvider(): array
    {
        return [
            [
                400,
                'Request malformed',
            ],
            [
                413,
                'Too many events in request',
            ],
            [
                429,
                'Too many requests for the device',
            ],
            [
                500,
                'Error while handling the request',
            ],
            [
                502,
                'Error while handling the request',
            ],
            [
                504,
                'Error while handling the request',
            ],
            [
                503,
                'Server error',
            ],
            [
                999,
                null,
            ],
        ];
    }

    /**
     * @dataProvider getTextTestDataProvider
     * @param int $code
     * @param mixed $expected
     */
    public function testGetTextReturnsExpected(int $code, $expected): void
    {
        $this->assertEquals($expected, ErrorCodes::getText($code));
    }
}
