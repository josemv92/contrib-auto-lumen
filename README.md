[![Releases](https://img.shields.io/badge/releases-purple)](https://github.com/josemv92/contrib-auto-lumen/releases)
[![Issues](https://img.shields.io/badge/issues-pink)](https://github.com/josemv92/contrib-auto-lumen/issues)

# OpenTelemetry Lumen auto-instrumentation

Auto-telemetry for the Lumen framework built with OpenTelemetry.

(Original built from [new999day/contrib-auto-lumen](https://github.com/new999day/contrib-auto-lumen))

## Overview
Provides instrumentation based on the Lumen framework functionality to generate spans based on the different actions the application can make.

This package is build based on the official Laravel instrumentation and it is up to date with the latest Lumen version.

>[!WARNING]
> Lumen framework is deprecated in favor of using Laravel. This package is mimicking the functionality the Laravel auto-instrumentation package had for v11.x. Suggestions for more functionality might be evaluated but won't be priority. Prefer moving to Laravel to get the latest security upgrades for the framework.

Auto-instrumentation hooks are registered via composer, and spans will automatically be created.

## Pre-requirements
- Lumen framework v11.x
- PHP OpenTelemetry

## Installation
You can install this package using Composer

```
composer require josemv92/opentelemetry-auto-lumen
```

## Configuration

The extension can be disabled via [runtime configuration](https://opentelemetry.io/docs/instrumentation/php/sdk/#configuration):

```shell
OTEL_PHP_DISABLED_INSTRUMENTATIONS=lumen
```

## Contributions
Pull requests are allowed, but since Lumen framework is already deprecated, this might not be a priority. Application follows PSR-12.
