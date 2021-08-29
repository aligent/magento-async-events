<?php

namespace Aligent\Webhooks\Service\Webhook;

use Aligent\Webhooks\Api\Data\WebhookInterface;
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
     * @var Client
     */
    private Client $client;

    /**
     * @var Json
     */
    private Json $json;

    /**
     * @var EncryptorInterface
     */
    private EncryptorInterface $encryptor;

    public function __construct(
        Client $client,
        Json $json,
        EncryptorInterface $encryptor
    ) {
        $this->client = $client;
        $this->json = $json;
        $this->encryptor = $encryptor;
    }

    /**
     * {@inheritDoc}
     */
    public function notify(WebhookInterface $webhook, array $data): NotifierResult
    {
        $body = $data;

        // Sign the payload that the client can verify. Which means a secret has to be provided when subscribing to a
        // webhook
        $headers = [
            self::HASHING_ALGORITHM => hash_hmac(
                self::HASHING_ALGORITHM,
                $this->json->serialize($body),
                $this->encryptor->decrypt($webhook->getVerificationToken())
            )
        ];

        $notifierResult = new NotifierResult();
        $notifierResult->setSubscriptionId($webhook->getSubscriptionId());

        try {
            $response = $this->client->post(
                $webhook->getRecipientUrl(),
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
