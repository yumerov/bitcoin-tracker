<?php

namespace Unit\Services;

use App\Bitcoin\Bitfinex\Response;
use App\Bitcoin\Bitfinex\ResponseAdapter;
use App\Bitcoin\Common\ClientInterface;
use App\Services\PriceSynchronizer;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class PriceSynchronizerTest extends TestCase
{

    private PriceSynchronizer $synchronizer;
    private ClientInterface $client;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->synchronizer = new PriceSynchronizer(
            $this->client,
            $this->logger
        );
    }

    public function testSynchronizeFailed()
    {
        // Arrange
        $exception = new \Exception('Manual fail');
        $this->client
            ->expects($this->once())
            ->method('get')
            ->willThrowException($exception);

        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with($exception->getMessage());

        // Act
        $this->synchronizer->synchronize();
    }

    public function testSynchronizeNoResponse()
    {
        // Arrange
        $this->client
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);

        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with('[PriceSynchronizer] Could not get response');

        // Act
        $this->synchronizer->synchronize();
    }

    public function testSynchronizeSuccessfully()
    {
        $this->markTestSkipped('Complete after setting in-memory database or another method');
        // Arrange
        $response = new ResponseAdapter(new Response(
            null,
            null,
            null,
            69000,
            null,
            null,
            null,
            1677407246
        ));
        $priceArray = [
            'id' => 1,
            'price' => $response->getPrice(),
            'timestamp' => $response->getTimestamp()
        ];

        $this->client
            ->expects($this->once())
            ->method('get')
            ->willReturn($response);

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with('[PriceSynchronizer] Stored successfully', $priceArray);

        // Act
        $this->synchronizer->synchronize();
    }
}
