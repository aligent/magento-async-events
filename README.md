# Webhooks 2.0

You’re viewing the documentation for Webhooks v2.0. For v1.x [click here](docs/README.md)

Table of Contents
-----------------

- [Webhooks 2.0](#webhooks-20)
  - [Introduction](#introduction)
  - [Concepts](#concepts)
    - [Webhook](#webhook)
    - [Recipient](#recipient)
  - [API](#api)
    - [NotifierFactoryInterface](#notifierfactoryinterface)
    - [NotifierInterface](#notifierinterface)
  - [Getting Started](#getting-started)
      - [Dependencies](#dependencies)
  - [Usage](#usage)
  - [License](#license)

Introduction
------------

Aligent/Webhooks is a flexible event driven webhook framework for Magento 2.

If you've never heard about webhooks before, [read this first](https://en.wikipedia.org/wiki/Webhook).

Concepts
--------

### Webhook

A **Webhook** represents a hook that recipients can hook into and listen for updates.

### Recipient

A **Recipient** is an entity that subscribes to one or multiple webhooks.

API
---

### NotifierFactoryInterface

The notifier factory interface provides the common interface for all notifier factories. The notifier factory serves as an abstract factory and will generate the concrete classes as required.

### NotifierInterface

The notifier interface provides the common interface for all notifer
implementations.


Getting Started
---------------

#### Dependencies

The module relies on `RabbitMQ` as the underlying queue message broker, therefore it needs to be enabled and configured.

Usage
-----

Creating webhooks is very straightforward. A hook is defined in a `webhooks.xml` specifing a unique name for the hook along with information on how to resolve it.

```xml
<webhook hook_name="custom">
    <service class="instance" method="method"/>
</webhook>
```

This is kind of similar to a `webapi.xml` where a route is specified along with which class and method to execute.

License
-------
