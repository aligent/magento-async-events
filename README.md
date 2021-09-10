# Webhooks 2.1
A framework for writing reliable and asynchronous webhooks with Magento. It is:

* **Asynchronous**: AW’s abstractions use RabbitMQ to leverage asynchronous message delivery.
* **Flexible**: Decoupling hooks and events provide greater flexibility in message modelling.
* **Scalable**: AW has minimal footprint and handles back pressure and provides an asynchronous failover model automatically.

Webhooks is an event-driven flexible webhooks module that allows you to dispatch anything to any application at any time asynchronously.

A core focus of the webhooks module is the re-use code. You can pretty much turn any Magento Web API into a webhook if you wanted to.

**This module requires `RabbitMQ`**

## Installation
### Composer

Add the project’s repository url to your composer.json

```jsonc
{
    "repositories": {
        "aligent/webhooks": {
            "type": "vcs",
            "url": "git@bitbucket.org:aligent/magento2-webhooks.git"
        }
    }
}
```

And then you can use composer to install the package.

`composer require aligent/webhooks`

## Configuration

The module does not come with defaults for notifiers and notifier factory which are important in the delivery of webhooks. However it provides reference implementations to provide a mental model of how it should be used. There is no reason to not use the reference implementation. If it suits your needs, you are free to use it.

Therefore if you decide to use the reference implementations, you have to configure it first.

This is done by making changes to your di.xml with the following
```xml
    <preference for="Aligent\Webhooks\Service\Webhook\NotifierFactoryInterface"
                type="Aligent\Webhooks\Service\Webhook\NotifierFactory" />

    <type name="Aligent\Webhooks\Service\Webhook\NotifierFactory">
        <arguments>
            <argument name="notifierClasses" xsi:type="array">
                <item name="default" xsi:type="object">Aligent\Webhooks\Service\Webhook\HttpNotifier</item>
            </argument>
        </arguments>
    </type>
```

## Creating Webhooks
The webhooks module reads a `webhooks.xml` file that allows for defining available webhooks.

```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:module:Aligent_Webhooks:etc/webhooks.xsd">

    <webhook hook_name="entity.verb">
        <service class="ServiceClass" method="method"/>
    </webhook>
    
</config>
```
In the above example we define the hook `entity.verb`

The naming convention for defining a hook name is having entities and a verb that describes what happened on the last entity. Entities can be chained together arbitrarily but the last separator must be a verb.

### Example
```xml
    <webhook hook_name="sales.order.created">
        <service class="Magento\Sales\Api\OrderRepositoryInterface" method="get"/>
    </webhook>
```
In the above example we've just defined a webhook `sales.order.created` which we will intend to dispatch this after an order is created. Although since the event and hook is completely decoupled, you can choose to dispatch this whenever you want such as before the quote is submitted or after the order is saved or etc.

## Creating subscribers
Now that you have a webhook defined, you can start having subscribers to it. The module provides an API interface that can be used to register subscribers.

`POST rest/v1/webhook`


```json
{
    "webhook": {
        "event_name": "sales.order.created",
        "recipient_url": "https://localhost:8080/order_handler",
        "verification_token": "fv38u07Wdh$R@mRd",
        "metadata": "default"
    }
}
```

## Dispatching Webhooks
Once you have a webhook defined, you'll have to trigger the webhook where desired. For example, you might want to trigger the webhook after the `sales_order_save_commit_after` or maybe inside plugin.

Dispatching a webhook is simply done by publishing to the webhook queue.

The `Magento\Framework\MessageQueue\PublisherInterface::publish` takes in two arguments

```php
    public function publish($topicName, $data);
```

The first argument `$topicName` should be a string that's defined by the constant `\Aligent\Webhooks\Helper\QueueMetadataInterface::WEBHOOK_QUEUE`

The second argument `$data` follows a specific structure. It should contain an array of two strings.

1. The first string specifies what webhook to dispatch.
2. The second string **should** be a `json` serialised string. The serialised string should contain the **named** arguments of the service method that resolves the webhook.
   1. For example, if your service method was `Magento\Sales\Api\OrderRepositoryInterface::get` which takes in the following inputs
   ```php
    /**
     * @param int $id The order ID.
     * @return \Magento\Sales\Api\Data\OrderInterface Order interface.
     */
    public function get($id);
    ```
    your `$data` should look like
    ```php
    $arguments = ['id' => $orderId];

    $data = ['sales.order.created', $this->json->serialize($arguments)]
    ```
This is likely to change in a future major version where a `WebhookMessage` would be passed instead of an array of strings.


### Example
In this example, the `sales.order.created` webhook is triggered using an event listener.

```php
    public function execute(Observer $observer): void
    {
        /** @var Order $object */
        $object = $observer->getEvent()->getData('order');

        $arguments = ['id' => $object->getId()];
        $data = ['sales.order.created', $this->json->serialize($arguments)];

        $this->publisher->publish(QueueMetadataInterface::WEBHOOK_QUEUE, $data);
    }
```
## Failover Architecture
![Webhook Failover Architecture!](./docs/failover_architecture.png "Webhook Failover Architecture")

## Securing Webhooks

## Testing Webhooks

## Warnings

## Appendix
For v1.x documentation [click here](docs/README.md)
