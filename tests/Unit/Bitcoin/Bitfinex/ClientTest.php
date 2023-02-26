<?php

namespace Unit\Bitcoin\Bitfinex;

use App\Bitcoin\Bitfinex\Client;
use App\Bitcoin\Bitfinex\Response;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Validation\Validator as ValidatorInterface;
use Illuminate\Support\MessageBag;
use PHPUnit\Framework\MockObject\Exception as MockerException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use Illuminate\Contracts\Validation\Factory as Validator;
use Psr\SimpleCache\CacheInterface;

class ClientTest extends TestCase
{

    private Client $client;
    private HttpClient $httpClient;
    private LoggerInterface $logger;
    private Validator $validator;
    private CacheInterface $cache;

    private const URL = 'https://api.bitfinex.com/v1/pubticker/btcusd';
    private const CACHE_TTL_IN_SECONDS = 30;
    private const CACHE_KEY = 'bitfinex_bitcoin_price';

    /**
     * @return void
     * @throws MockerException
     */
    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClient::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->validator = $this->createMock(Validator::class);
        $this->cache = $this->createMock(CacheInterface::class);

        $this->client = new Client(
            $this->httpClient,
            $this->logger,
            $this->validator,
            $this->cache
        );
    }

    public function testReadingFromCache()
    {
        // Arrange
        $mockedResponse = $this->createResponseDTO();
        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with(self::CACHE_KEY)
            ->willReturn($mockedResponse);

        // Act
        $response = $this->client->get();

        // Assert
        $this->assertEquals($mockedResponse->lastPrice, $response->getPrice());
    }

    public function testCallingApiSuccessfully()
    {
        // Arrange
        $mockedResponse = $this->createResponseDTO();
        $httpResponse = $this->createHttpResponse(response: $mockedResponse);
        $responseContent = $httpResponse->getBody()->getContents();

        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with(self::CACHE_KEY)
            ->willReturn(null);

        $this->httpClient
            ->expects($this->once())
            ->method('get')
            ->with(self::URL)
            ->willReturn($httpResponse);

        $this->validator
            ->expects($this->once())
            ->method('make')
            ->with(
                json_decode($responseContent, true),
                [
                    'last_price' => 'required',
                    'timestamp' => 'required',
                ])
            ->willReturn($this->createValidator());

        $this->cache
            ->expects($this->once())
            ->method('set')
            ->with(self::CACHE_KEY, $mockedResponse, self::CACHE_TTL_IN_SECONDS);

        // TODO: uncomment when find how to mock twice with different params
//        $this->logger
//            ->expects($this->once())
//            ->method('info')
//            ->with('[Bitfinex] Raw response: ' . $responseContent);
//
//        $this->logger
//            ->expects($this->once())
//            ->method('info')
//            ->with('[Bitfinex] Get the price successfully: ' . $mockedResponse->lastPrice);

        // Act
        $response = $this->client->get();

        // Assert
        $this->assertEquals($mockedResponse->lastPrice, $response->getPrice());
    }

    private function createResponseDTO(): Response
    {
        return new Response(
            23266.5,
            23266.0,
            23267.0,
            23266.0,
            22810.0,
            23302.0,
            980.72109237,
            1677407589.3839
        );
    }

    private function createHttpResponse(Response $response, int $status = 200): ResponseInterface
    {
        return new class($status, $response) implements ResponseInterface {

            public function __construct(
                private readonly int $status,
                private readonly Response $response
            ) { }

            public function getProtocolVersion() { }

            public function withProtocolVersion($version) { }

            public function getHeaders() { }

            public function hasHeader($name) { }

            public function getHeader($name) { }

            public function getHeaderLine($name) { }

            public function withHeader($name, $value) { }

            public function withAddedHeader($name, $value) { }

            public function withoutHeader($name) { }

            public function getBody(): StreamInterface
            {
                return new class($this->response) implements StreamInterface {

                    public function __construct(
                        private readonly Response $response
                    ) { }

                    public function __toString() { return ''; }

                    public function close() { }

                    public function detach() { }

                    public function getSize() { }

                    public function tell() { }

                    public function eof() { }

                    public function isSeekable() { }
                    public function seek($offset, $whence = SEEK_SET) { }

                    public function rewind() { }

                    public function isWritable() { }

                    public function write($string) { }

                    public function isReadable() { }

                    public function read($length) { }

                    public function getContents(): string
                    {
                        return "{\"mid\":\"{$this->response->mid}\","
                            . "\"bid\":\"{$this->response->bid}\","
                            . "\"ask\":\"{$this->response->ask}\","
                            . "\"last_price\":\"{$this->response->lastPrice}\","
                            . "\"low\":\"{$this->response->low}\","
                            . "\"high\":\"{$this->response->high}\","
                            . "\"volume\":\"{$this->response->volume}\","
                            . "\"timestamp\":\"{$this->response->timestamp}\""
                            . "}";
                    }

                    public function getMetadata($key = null) { }
                };
            }

            public function withBody(StreamInterface $body) { }

            public function getStatusCode(): int
            {
                return $this->status;
            }

            public function withStatus($code, $reasonPhrase = '') { }

            public function getReasonPhrase() { }
        };
    }

    private function createValidator(): ValidatorInterface
    {
        return new class implements ValidatorInterface {

            public function getMessageBag() { }

            public function validate() { }

            public function validated() { }

            public function fails(): bool
            {
                return false;
            }

            public function failed() { }

            public function sometimes($attribute, $rules, callable $callback) { }

            public function after($callback) { }

            public function errors(): MessageBag
            {
                return new MessageBag();
            }
        };
    }
}
