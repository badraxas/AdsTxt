<?php

use Badraxas\Adstxt\AdsTxt;
use Badraxas\Adstxt\AdsTxtFetcher;
use Badraxas\Adstxt\Enums\Relationship;
use Badraxas\Adstxt\Exceptions\AdsTxtParser\UrlOpenException;
use Badraxas\Adstxt\Lines\Record;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

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
            new Record(
                'exampleDomain.com',
                '12345',
                Relationship::DIRECT,
                'd12345678f01234'
            )
        ), $result);
    }

    public function testParseFromUrlInvalidUrl(): void
    {
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
            ->willReturn(404)
        ;

        $fetcher = new AdsTxtFetcher($clientMock, $requestFactoryMock);

        $this->expectException(UrlOpenException::class);
        $fetcher->fromUrl('https://example.com/ads.txt');
    }
}
