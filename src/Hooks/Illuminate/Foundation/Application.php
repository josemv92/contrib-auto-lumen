<?php

declare(strict_types=1);

namespace OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks\Illuminate\Foundation;

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Laravel\Lumen\Application as FoundationalApplication;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks\LumenHook;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks\LumenHookTrait;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\CacheWatcher;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\ClientRequestWatcher;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\ExceptionWatcher;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\LogWatcher;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\QueryWatcher;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\RedisCommand\RedisCommandWatcher;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Watchers\Watcher;
use function OpenTelemetry\Instrumentation\hook;
use Throwable;

class Application implements LumenHook
{
    use LumenHookTrait;

    public function instrument(): void
    {
        /** @psalm-suppress UnusedFunctionCall */
        hook(
            FoundationalApplication::class,
            '__construct',
            post: function (FoundationalApplication $application, array $_params, mixed $_returnValue, ?Throwable $_exception) {
                $this->registerWatchers($application, new CacheWatcher());
                $this->registerWatchers($application, new ClientRequestWatcher($this->instrumentation));
                $this->registerWatchers($application, new ExceptionWatcher());
                $this->registerWatchers($application, new LogWatcher($this->instrumentation));
                $this->registerWatchers($application, new QueryWatcher($this->instrumentation));
            },
        );
    }

    private function registerWatchers(FoundationalApplication $app, Watcher $watcher): void
    {
        $watcher->register($app);
    }
}
