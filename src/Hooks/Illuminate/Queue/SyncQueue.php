<?php

declare(strict_types=1);

namespace OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks\Illuminate\Queue;

use Illuminate\Queue\SyncQueue as LumenSyncQueue;
use OpenTelemetry\API\Trace\SpanKind;
use OpenTelemetry\Context\Context;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks\LumenHook;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks\LumenHookTrait;
use OpenTelemetry\Contrib\Instrumentation\Lumen\Hooks\PostHookTrait;
use function OpenTelemetry\Instrumentation\hook;
use OpenTelemetry\SemConv\TraceAttributes;
use Throwable;

class SyncQueue implements LumenHook
{
    use AttributesBuilder;
    use LumenHookTrait;
    use PostHookTrait;

    public function instrument(): void
    {
        $this->hookPush();
    }

    /** @psalm-suppress PossiblyUnusedReturnValue */
    protected function hookPush(): bool
    {
        return hook(
            LumenSyncQueue::class,
            'push',
            pre: function (LumenSyncQueue $queue, array $params, string $class, string $function, ?string $filename, ?int $lineno) {
                /** @psalm-suppress ArgumentTypeCoercion */
                $span = $this->instrumentation
                    ->tracer()
                    ->spanBuilder(vsprintf('%s %s', [
                        $queue->getConnectionName(),
                        'process',
                    ]))
                    ->setSpanKind(SpanKind::KIND_INTERNAL)
                    ->setAttributes([
                        TraceAttributes::CODE_FUNCTION_NAME => sprintf('%s::%s', $class, $function),
                        TraceAttributes::CODE_FILE_PATH => $filename,
                        TraceAttributes::CODE_LINE_NUMBER => $lineno,
                    ])
                    ->startSpan();

                Context::storage()->attach($span->storeInContext(Context::getCurrent()));
            },
            post: function (LumenSyncQueue $queue, array $params, mixed $returnValue, ?Throwable $exception) {
                $this->endSpan($exception);
            },
        );
    }
}
