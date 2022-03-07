<?php

namespace LZI\DataCite;

use LZI\DataCite\Metadata\DataCiteRecord;
use stdClass;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class DataCiteClient
{
    const ERROR_PREFIX = '!! ERROR ';

    const ENDPOINT_DOIS = 'dois/';

    const STATE_FINDABLE = 'findable';
    const STATE_REGISTERED = 'registered';

    const EVENTS = [
        self::STATE_FINDABLE => 'publish',
        self::STATE_REGISTERED => 'hide'
    ];

    private string $providerUrl;
    private ?HttpClientInterface $httpClient;
    private ?ResponseInterface $response = NULL;

    private $status = NULL;
    private ?Throwable $exception = NULL;

    public function __construct(string $username, string $password, string $providerUrl) {
        $this->providerUrl = str_replace('{{ CREDENTIALS }}', $username.':'.$password, $providerUrl);
        $this->httpClient = HttpClient::create();
    }

    public function lastRequestFailed() : bool
    {
        return !($this->status == 200 OR $this->status == 201);
    }

    public function getUrl() : string
    {
        $parts =  explode('@', $this->providerUrl);

        return $parts[1] ?? 'ERROR';
    }

    public function getResponse() : ?ResponseInterface
    {
        return $this->response;
    }

    public function getStatus() : ?int
    {
        $statusCode = NULL;

        if ($this->response instanceof ResponseInterface) {

            try {
                $statusCode = $this->response->getStatusCode();
            } catch (Throwable $ex) { }
        }

        return $statusCode;
    }

    public function getException() : ?Throwable
    {
        return $this->exception;
    }

    public function getErrorMessage() : string
    {
        if ($this->exception !== NULL) {
            return self::ERROR_PREFIX. $this->exception->getMessage();
        }

        return $this->lastRequestFailed()
            ? self::ERROR_PREFIX. 'DataCiteClient - Status of last response: '.$this->status
            : '';
    }

    private function getResponseBody() : ?stdClass
    {
        $responseBody = NULL;

        if ($this->response instanceof ResponseInterface) {
            try {
                $responseBody = json_decode($this->response->getContent());
            }
            catch(Throwable $ex) { }
        }

        return $this->lastRequestFailed()
            ? NULL
            : $responseBody;
    }

    private function makeRequest(...$requestParams) : ?stdClass
    {
        $data = NULL;

        try {
            $this->exception = NULL;
            $this->status = NULL;
            $this->response = NULL;
            $this->response = $this->httpClient->request(...$requestParams);
            $this->status = $this->response->getStatusCode();

            $data = $this->getResponseBody();
        }
        catch(TransportExceptionInterface $ex) {
            $this->response = NULL;
            $this->exception = $ex;
        }

        return $data;
    }

    public function getDataCiteRecord(string $doi) : ?DataCiteRecord
    {
        $metadata = $this->makeRequest('GET', $this->providerUrl.self::ENDPOINT_DOIS.$doi);

        return ($this->lastRequestFailed() OR $metadata === NULL)
            ? NULL
            : new DataCiteRecord($metadata);

    }

    public function updateDataCiteRecord(DataCiteRecord $dataCiteRecord) : ?DataCiteRecord
    {
        $metadata = $this->makeRequest(
            'PUT',
            $this->providerUrl.self::ENDPOINT_DOIS.$dataCiteRecord->getDoi(),
            [
                'headers' => [ 'Content-type: application/json' ],
                'body' => $dataCiteRecord->toApiJson()
            ]
        );

        return ($this->lastRequestFailed() OR $metadata === NULL)
            ? NULL
            : new DataCiteRecord($metadata);
    }

    /**
     * see the self::STATE_... constants for possible states
     */
    public function setDoiState(string $doi, string $state) : ?DataCiteRecord
    {
        $data =  [
            'data' => [
                'id' => $doi,
                'type' => 'dois',
                'attributes' => [ 'event' => self::EVENTS[$state] ]
            ]
        ];

        $metadata = $this->makeRequest(
            'PUT',
            $this->providerUrl.self::ENDPOINT_DOIS.$doi,
            [
                'headers' => [ 'Content-type: application/json' ],
                'body' => json_encode($data)
            ]
        );

        return ($this->lastRequestFailed() OR $metadata === NULL)
            ? NULL
            : new DataCiteRecord($metadata);
    }
}