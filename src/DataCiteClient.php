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

    /**
     * @var HttpClientInterface|NULL
     */
    private $httpClient;

    /**
     * @var string
     */
    private $providerUrl;

    /**
     * @var ResponseInterface|null
     */
    private $response = NULL;

    private $status = NULL;
    private $exception = NULL;

    public function __construct(string $username, string $password, string $providerUrl) {
        $this->providerUrl = str_replace('{{ CREDENTIALS }}', $username.':'.$password, $providerUrl);
        $this->httpClient = HttpClient::create();
    }

    /**
     * @return bool
     */
    public function lastRequestFailed()
    {
        return !($this->status == 200 OR $this->status == 201);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $parts =  explode('@', $this->providerUrl);

        return $parts[1] ?? 'ERROR';
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return int|null
     */
    public function getStatus()
    {
        $statusCode = NULL;

        if ($this->response instanceof ResponseInterface) {

            try {
                $statusCode = $this->response->getStatusCode();
            } catch (Throwable $ex) { }
        }

        return $statusCode;
    }

    /** @return Throwable */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        if ($this->exception !== NULL) {
            return self::ERROR_PREFIX. $this->exception->getMessage();
        }

        return $this->lastRequestFailed()
            ? self::ERROR_PREFIX. 'DataCiteClient - Status of last response: '.$this->status
            : '';
    }

    /**
     * @return stdClass|null
     */
    private function getResponseBody()
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

    private function makeRequest(...$requestParams)
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

    /**
     * @param string $doi
     * @return DataCiteRecord|null
     */
    public function getDataCiteRecord(string $doi)
    {
        $metadata = $this->makeRequest('GET', $this->providerUrl.self::ENDPOINT_DOIS.$doi);

        return ($this->lastRequestFailed() AND $metadata !== NULL)
            ? new DataCiteRecord($metadata)
            : NULL;
    }

    /**
     * @param DataCiteRecord $dataCiteRecord
     * @return DataCiteRecord|null
     */
    public function updateDataCiteRecord(DataCiteRecord $dataCiteRecord)
    {
        $metadata = $this->makeRequest(
            'PUT',
            $this->providerUrl.self::ENDPOINT_DOIS.$dataCiteRecord->getDoi(),
            [
                'headers' => [ 'Content-type: application/json' ],
                'body' => $dataCiteRecord->toApiJson()
            ]
        );

        return ($this->lastRequestFailed() AND $metadata !== NULL)
            ? NULL
            : new DataCiteRecord($metadata);
    }

    /**
     * @param string $doi
     * @param string $state
     * @return DataCiteRecord|null
     *
     * see the self::STATE_... constants for possible states
     */
    public function setDoiState(string $doi, string $state)
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

        return ($this->lastRequestFailed() AND $metadata !== NULL)
            ? NULL
            : new DataCiteRecord($metadata);
    }
}