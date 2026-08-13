<?php

declare(strict_types=1);

namespace OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks;

use OpenTelemetry\API\Instrumentation\CachedInstrumentation;

interface LumenHook
{
    /** @psalm-suppress PossiblyUnusedReturnValue */
    public static function hook(CachedInstrumentation $instrumentation): LumenHook;

    public function instrument(): void;
}
