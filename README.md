# Progressive Bundle

Symfony integration for the feature-flag library [Progressive](https://github.com/antfroger/progressive)

[![Build Status](https://github.com/antfroger/progressive-bundle/workflows/CI/badge.svg)](https://github.com/antfroger/progressive-bundle)

## Installation

Install this bundle using Composer:

```console
$ composer require antfroger/progressive-bundle
```

## Configuration

### 1. Enable the Bundle

First, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Af\ProgressiveBundle\AfProgressiveBundle::class => ['all' => true],
];
```

### 2. Configure the Bundle

Create the configuration file `config/packages/af_progressive.yaml`.

The only required key is `config`.
The key needs the path of the yaml file where you will configure the features of your application.  
An example configuration looks like this:

```yaml
# config/packages/af_progressive.yaml
af_progressive:
  config: '%kernel.project_dir%/config/features.yaml'
  context:
    env: '%kernel.environment%'
```

```yaml
# config/features.yaml
features:
  dark-theme: true
  call-center:
    between-hours:
      start: 8
      end: 20
  homepage-v2:
    partial:
      env: ['dev', 'preprod']
      roles: ['ROLE_ADMIN', 'ROLE_DEV']
```

Look at the [Progressive documentation](https://github.com/antfroger/progressive#usage) to know more about the feature's configuration.

## Usage

You can use Progressive in a controller:

```php
  public function info(Progressive $progressive): Response
  {
      if ($progressive->isEnabled('call-center')) {
          // Do what you want when the feature `call-center` is enabled
      }
  }
```

Or in a template:

```twig
{% if is_enabled('call-center') %}
    {# Do what you want when the feature `call-center` is enabled #}
{% endif %}
```

## Create your own rules

I'm sure that soon you will want to create your own rules to progressively enable features dependning on your application logic.  
That's where custom rules come into play! (More information about **custom rules** on the [Progressive doc](https://github.com/antfroger/progressive#custom))

To create your own rules and use them in your `feature. yaml` file, you only need to create a class extending `Progressive\Rule\RuleInterface`.  
That's it!  
Symfony autowiring takes care of the rest.

Let's say you want to display a chat in your contact page, but only in working hours (for instance between 9am and 7pm).

1. First, create the rule:

```php
// src/Progressive/BetweenHours.php
namespace App\Progressive;

use Progressive\ParameterBagInterface;
use Progressive\Rule\RuleInterface;

class BetweenHours implements RuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'between-hours';
    }

    /**
     * {@inheritdoc}
     */
    public function decide(ParameterBagInterface $bag, array $hours = []): bool
    {
        if (!isset($hours['start']) || !is_int($hours['start']) || !isset($hours['end']) || !is_int($hours['end'])) {
            return false;
        }

        $now = new \DateTime();
        $hour = $now->format('H');
        return $hours['start'] <= $hour && $hour < $hours['end'];
    }
}
```

2. Now, you can use this new rule, in the `feature. yaml` file

```yaml
features:
  customer-service-chat:
    between-hours: # same as `BetweenHours::getName()`
      start: 9
      end: 19

```

3. You now have a feature using this new rule.  
Let's use it in a controller or in a template:

```php
public function customerService(Progressive $progressive): Response
  {
      if ($progressive->isEnabled('customer-service-chat')) {
        // ...
      }
  }
```

```twig
{% if is_enabled('customer-service-chat') %}
    {# Display the chat #}
{% endif %}
```
