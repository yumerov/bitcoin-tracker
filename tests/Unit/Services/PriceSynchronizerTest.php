<?php

namespace Unit\Services;

use App\Bitcoin\Bitfinex\Response;
use App\Bitcoin\Bitfinex\ResponseAdapter;
use App\Bitcoin\Common\ClientInterface;
use App\Services\PriceSynchronizer;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\Exception as MockException;
use Psr\Log\LoggerInterface;
use Tests\TestCase;
use Exception;

class PriceSynchronizerTest extends TestCase
{

    use RefreshDatabase;
    private PriceSynchronizer $synchronizer;
    private ClientInterface $client;
    private LoggerInterface $logger;
    private Dispatcher $dispatcher;

    /**
     * @return void
     * @throws MockException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshInMemoryDatabase();

        $this->client = $this->createMock(ClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->dispatcher = $this->createMock(Dispatcher::class);

        $this->synchronizer = new PriceSynchronizer(
            $this->client,
            $this->logger,
            $this->dispatcher
        );
    }

    public function test_synchronize_failed()
    {
        // Arrange
        $exception = new Exception('Manual fail');
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

    public function test_synchronize_no_response()
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

    public function test_synchronize_successfully()
    {
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
            'timestamp' => Carbon::parse($response->getTimestamp())->format('Y-m-d H:i:s')
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
