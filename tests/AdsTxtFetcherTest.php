<?php

use Badraxas\Adstxt\AdsTxt;
use Badraxas\Adstxt\AdsTxtFetcher;
use Badraxas\Adstxt\Enums\AccountType;
use Badraxas\Adstxt\Lines\Vendor;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @internal
 *
 * @coversNothing
 */
class AdsTxtFetcherTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testParseFromUrl(): void
    {
        $expectedContent = 'exampleDomain.com, 12345, DIRECT, d12345678f01234';

        $clientMock = $this->createMock(ClientInterface::class);
        $requestFactoryMock = $this->createMock(RequestFactoryInterface::class);
        $requestMock = $this->createMock(RequestInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);
        $streamMock = $this->createMock(StreamInterface::class);

        $requestFactoryMock->method('createRequest')
            ->willReturn($requestMock)
        ;
        $clientMock->method('sendRequest')
            ->willReturn($responseMock)
        ;
        $responseMock->method('getStatusCode')
            ->willReturn(200)
        ;

        $streamMock->method('getContents')
            ->willReturn($expectedContent)
        ;

        $responseMock->method('getBody')
            ->willReturn($streamMock)
        ;

        $fetcher = new AdsTxtFetcher($clientMock, $requestFactoryMock);

        $result = $fetcher->fromUrl('https://example.com/ads.txt');

        $this->assertInstanceOf(AdsTxt::class, $result);

        $this->assertEquals((new AdsTxt())->addLine(
            new Vendor(
                'exampleDomain.com',
                '12345',
                AccountType::DIRECT,
                'd12345678f01234'
            )
        ), $result);
    }
}
