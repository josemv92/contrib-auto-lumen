<?php

declare(strict_types=1);

namespace OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks;

use OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks\LumenHook;
use OpenTelemetry\API\Instrumentation\CachedInstrumentation;

trait LumenHookTrait
{
    private static LumenHook $instance;

    protected function __construct(
        protected CachedInstrumentation $instrumentation,
    ) {
    }

    abstract public function instrument(): void;

    /** @psalm-suppress PossiblyUnusedReturnValue */
    public static function hook(CachedInstrumentation $instrumentation): LumenHook
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        if (!isset(self::$instance)) {
            /** @phan-suppress-next-line PhanTypeInstantiateTraitStaticOrSelf,PhanTypeMismatchPropertyReal */
            self::$instance = new self($instrumentation);
            self::$instance->instrument();
        }

        return self::$instance;
    }
}
