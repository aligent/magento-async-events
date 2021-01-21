<?php

namespace Aligent\Webhooks\Service\Webhook;

use GuzzleHttp\Client;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Serialize\Serializer\Json;

class HttpNotifier implements NotifierInterface
{
    /**
     * Hash algorithm. Changing this in future will be a breaking change
     */
    private const HASHING_ALGORITHM = 'sha256';

    /**
     * @var string
     */
    private string $subscriptionId;

    /**
     * @var string
     */
    private string $objectId;

    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    private string $secret;

    /**
     * @var Json
     */
    private Json $json;

    /**
     * @var EncryptorInterface
     */
    private EncryptorInterface $encryptor;

    public function __construct(
        string $subscriptionId,
        string $objectId,
        string $url,
        string $secret,
        Client $client,
        Json $json,
        EncryptorInterface $encryptor
    ) {
        $this->subscriptionId = $subscriptionId;
        $this->objectId = $objectId;
        $this->client = $client;
        $this->url = $url;
        $this->secret = $secret;
        $this->json = $json;
        $this->encryptor = $encryptor;
    }

    /**
     * {@inheritDoc}
     */
    public function notify(): bool
    {

        $body = [
            'objectId' => $this->objectId
        ];

        // Sign the payload that the client can verify. Which means a secret has to be provided when subscribing to a
        // webhook
        $headers = [
            self::HASHING_ALGORITHM => hash_hmac(
                self::HASHING_ALGORITHM,
                $this->json->serialize($body),
                $this->encryptor->decrypt($this->secret)
            )
        ];

        $response = $this->client->post(
            $this->url,
            [
                'headers' => $headers,
                'json' => $body
            ]
        );

        return $response->getstatusCode() >= 200 && $response->getstatusCode() < 300;
    }
}
