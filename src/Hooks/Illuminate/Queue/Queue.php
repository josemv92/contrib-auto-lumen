<?php

declare(strict_types=1);

namespace OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks\Illuminate\Queue;

use Illuminate\Queue\Queue as AbstractQueue;
use OpenTelemetry\API\Trace\Propagation\TraceContextPropagator;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks\LumenHook;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks\LumenHookTrait;
use function OpenTelemetry\Instrumentation\hook;
use Throwable;

class Queue implements LumenHook
{
    use AttributesBuilder;
    use LumenHookTrait;

    public function instrument(): void
    {
        $this->hookAbstractQueueCreatePayloadArray();
    }

    /** @psalm-suppress PossiblyUnusedReturnValue */
    protected function hookAbstractQueueCreatePayloadArray(): bool
    {
        return hook(
            AbstractQueue::class,
            'createPayloadArray',
            post: function (AbstractQueue $_queue, array $_params, array $payload, ?Throwable $_exception): array {
                TraceContextPropagator::getInstance()->inject($payload);

                return $payload;
            },
        );
    }
}
