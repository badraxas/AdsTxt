<?php

namespace Badraxas\Adstxt;

use Badraxas\Adstxt\Exceptions\AdsTxtParser\UrlOpenException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * Class AdsTxtFatcher.
 *
 * A class to get content of a remote ads.txt files and converting them to AdsTxt instances.
 *
 * @psalm-api
 */
class AdsTxtFetcher
{
    public function __construct(private readonly ClientInterface $client, private readonly RequestFactoryInterface $requestFactory) {}

    /**
     * Creates an AdsTxt instance by fetching and parsing the content of an ads.txt file from a URL.
     *
     * @param string $url The URL of the ads.txt file.
     *
     * @return AdsTxt returns an instance of AdsTxt containing the parsed data
     *
     * @throws UrlOpenException if the URL cannot be accessed or content cannot be retrieved
     */
    public function fromUrl(string $url): AdsTxt
    {
        try {
            $request = $this->requestFactory->createRequest('GET', $url);

            $response = $this->client->sendRequest($request);

            if (200 !== $response->getStatusCode()) {
                throw new UrlOpenException('Unable to access URL or file not found');
            }

            $content = $response->getBody()->getContents();
        } catch (\Throwable $t) {
            throw new UrlOpenException(sprintf('Cannot fetch content from URL %s. Error: %s', $url, $t->getMessage()));
        }

        return (new AdsTxtParser())->fromString($content);
    }
}
