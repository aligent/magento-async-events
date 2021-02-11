<?php

namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Helper\NotifierResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class HttpNotifier
 *
 * This notifier just serves as a bare minimum and example implementation reference. You would want to create your own
 * factory and then derive your own implementations. However, if this just serves your purpose well, then you might as
 * well as use this instead.
 *
 */
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
    public function notify(): NotifierResult
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

        // TODO: should we just get rid of the NotifierResult, and return a WebhookLog model instead? that way we don't
        // need to unwrap this object into a WebhookLog model later, and then save that to the db.
        $notifierResult = new NotifierResult();
        $notifierResult->setSubscriptionId($this->subscriptionId);

        try {
            $response = $this->client->post(
                $this->url,
                [
                    'headers' => $headers,
                    'json' => $body
                ]
            );

            $notifierResult->setSuccess(
                $response->getStatusCode() >= 200
                && $response->getStatusCode() < 300
            );

            $notifierResult->setResponseData(
                $this->json->serialize(
                    $response->getBody()->getContents()
                )
            );
        } catch (GuzzleException $exception) {
            $notifierResult->setSuccess(false);

            $notifierResult->setResponseData(
                $this->json->serialize(
                    $exception->getMessage()
                )
            );
        }

        return $notifierResult;
    }
}
