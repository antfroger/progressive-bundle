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
