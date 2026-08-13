<?php

declare(strict_types=1);

namespace OpenTelemetry\Contrib\Instrumentation\Lumen;

use OpenTelemetry\SDK\Common\Configuration\Configuration;

use Laravel\Lumen\Application;
use OpenTelemetry\API\Instrumentation\CachedInstrumentation;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\CacheWatcher;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\ClientRequestWatcher;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\ExceptionWatcher;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\LogWatcher;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\QueryWatcher;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\Watcher;
use function OpenTelemetry\Instrumentation\hook;
use Throwable;

class LumenInstrumentation
{
    public const NAME = 'lumen';

    public static function registerWatchers(Application $app, Watcher $watcher)
    {
        $watcher->register($app);
    }

    public static function register(): void
    {
        $instrumentation = new CachedInstrumentation('io.opentelemetry.contrib.php.lumen');

        Hooks\Illuminate\Console\Command::hook($instrumentation);
        Hooks\Illuminate\Contracts\Console\Kernel::hook($instrumentation);
        Hooks\Illuminate\Contracts\Queue\Queue::hook($instrumentation);
        Hooks\Illuminate\Queue\SyncQueue::hook($instrumentation);
        Hooks\Illuminate\Database\Eloquent\Model::hook($instrumentation);
        Hooks\Illuminate\Foundation\Application::hook($instrumentation);
        Hooks\Illuminate\Queue\Queue::hook($instrumentation);
        Hooks\Illuminate\Queue\Worker::hook($instrumentation);
        HttpInstrumentation::register($instrumentation);
    }

    public static function shouldTraceCli(): bool
    {
        return PHP_SAPI !== 'cli' || (
            class_exists(Configuration::class)
            && Configuration::getBoolean('OTEL_PHP_TRACE_CLI_ENABLED', false)
        );
    }
}
