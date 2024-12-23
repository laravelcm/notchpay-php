<?php

namespace NotchPay\ApiOperations;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use NotchPay\Exceptions\ApiException;
use NotchPay\Exceptions\InvalidArgumentException;
use NotchPay\Exceptions\NotchPayException;
use NotchPay\NotchPay;
use GuzzleHttp\Client;
use NotchPay\Util\Util;

/**
 * Trait for resources that need to make API requests.
 */
trait Request
{

    protected static $client;


    protected static mixed $response;


    public static function validateParams(mixed $params = null, bool $required = false): void
    {
        if ($required) {
            if (empty($params) || !is_array($params)) {
                $message = 'The parameter passed must be an array and must not be empty';

                throw new InvalidArgumentException($message);
            }
        }
        if ($params && !is_array($params)) {
            $message = 'The parameter passed must be an array';

            throw new InvalidArgumentException($message);
        }
    }

    public static function staticRequest(string $method, string $url, array $params = [], string $return_type = 'obj'): array|object
    {


        if ($return_type != 'arr' && $return_type != 'obj') {
            throw new InvalidArgumentException('Return type can only be obj or arr');
        }

        static::setHttpResponse($method, $url, $params);


        if ($return_type == 'arr') {
            return static::getResponseData();
        }

        return Util::convertArrayToObject(static::getResponse());
    }

    /**
     * Set options for making the Client request.
     */
    protected static function setRequestOptions(): void
    {
        $headers = [
            'Authorization' => NotchPay::$apiKey,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'User-Agent'    => 'NotchPay/PHP Client',
        ];

        if (NotchPay::$apiPrivateKey) {
            $headers['X-Grant'] = NotchPay::$apiPrivateKey;
        }

        static::$client = new Client([
            'base_uri' => NotchPay::$apiBase,
            'verify'   => false,
            'headers'  => $headers,
        ]);
    }


    /**
     * @throws ApiException
     */
    private static function setHttpResponse(string $method, string $url, array $body = []): \GuzzleHttp\Psr7\Response
    {
        static::setRequestOptions();

        $finalUrl = NotchPay::$apiBase . '/' . $url;

        error_log('Final URL: ' . $finalUrl);
        error_log('Request Body: ' . json_encode($body));

        try {
            static::$response = static::$client->{strtolower($method)}(
                $finalUrl,
                ['body' => json_encode($body)]
            );
        } catch (ClientException | ServerException | ConnectException $e) {
            if ($e instanceof ConnectException) {
                throw new ApiException("Notch Pay Server unreachable");
            }

            $errors = self::getResponseErrors($e);
            error_log('Error Details: ' . print_r($errors, true));

            throw new ApiException(
                self::getResponseErrorMessage($e) ?? 'An unknown error occurred',
                $errors
            );
        }

        return static::$response;
    }



    private static function getResponseErrorMessage($e): string|null
    {
        return $e->getResponse()->getReasonPhrase();
    }

    private static function getResponseErrors($e): array
    {
        $body = $e->getResponse()->getBody()->getContents();
        error_log('Response Body: ' . $body);

        $decoded = json_decode($body, true);
        return is_array($decoded) ? $decoded : [];
    }



    private static function getResponse(): array
    {

        return json_decode(static::$response->getBody(), true);
    }


    private static function getResponseData(): array
    {
        return json_decode(static::$response->getBody(), true)['data'];
    }
}
