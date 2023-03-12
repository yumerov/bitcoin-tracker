<?php

// phpcs:disable Squiz.Formatting.OperatorBracket.MissingBrackets
// phpcs:disable Squiz.WhiteSpace.ObjectOperatorSpacing.Before

namespace App\Bitcoin\Bitfinex;

use App\Bitcoin\Common\ClientInterface;
use App\Bitcoin\Common\ResponseInterface;
use GuzzleHttp\Client as HttpClient;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Illuminate\Contracts\Validation\Factory as Validator;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class Client implements ClientInterface
{

    private const URL = 'https://api.bitfinex.com/v1/pubticker/btcusd';
    private const CACHE_TTL_IN_SECONDS = 30;
    private const CACHE_KEY = 'bitfinex_bitcoin_price';


    public function __construct(
        private readonly HttpClient $client,
        private readonly LoggerInterface $logger,
        private readonly Validator $validator,
        private readonly CacheInterface $cache
    ) { }

    public function get(): ?ResponseInterface
    {
        try {
             $apiResponse = $this->cache->get(self::CACHE_KEY);

             if ($apiResponse === null) {
                 $apiResponse = $this->callApi();
                 $this->cache->set(self::CACHE_KEY, $apiResponse, self::CACHE_TTL_IN_SECONDS);
                 $this->logger->info('[Bitfinex] Get the price successfully: '.$apiResponse->lastPrice);
             }

            return new ResponseAdapter($apiResponse);
        } catch (Exception | GuzzleException | InvalidArgumentException $ex) {
            $this->logger->error($ex->getMessage());
        }

        return null;
    }

    /**
     * @return Response
     * @throws GuzzleException
     */
    private function callApi(): Response
    {
        $response = $this->client->get(self::URL);

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new Exception('[Bitfinex] '.$statusCode.' is an invalid status code');
        }

        $jsonString = $response->getBody()->getContents();
        if (empty($jsonString) === true) {
            throw new Exception('[Bitfinex] Empty response');
        }

        $decodedResponse = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('[Bitfinex] '.json_last_error_msg());
        }

        $this->logger->info('[Bitfinex] Raw response: '.$jsonString);

        $validator = $this->validator->make($decodedResponse, [
            'last_price' => 'required',
            'timestamp' => 'required',
        ]);

        if ($validator->fails() === true) {
            $errors = implode(', ', collect($validator->errors()->getMessages())
                ->map(fn ($error) => $error[0])
                ->toArray());

            throw new Exception('[Bitfinex] '.$errors);
        }

        return new Response(
            floatval($decodedResponse['mid'] ?? null),
            floatval($decodedResponse['bid'] ?? null),
            floatval($decodedResponse['ask'] ?? null),
            floatval($decodedResponse['last_price'] ?? null),
            floatval($decodedResponse['low'] ?? null),
            floatval($decodedResponse['high'] ?? null),
            floatval($decodedResponse['volume'] ?? null),
            floatval($decodedResponse['timestamp'] ?? null),
        );
    }
}
