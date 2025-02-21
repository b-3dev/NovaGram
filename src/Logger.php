<?php

namespace NanoGram\NanoGram;

use Monolog\Logger as log;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Logger {
    private static ?log $log = null;

    public static function init(): void {
        if (self::$log === null) {
            self::$log = new log('Bot');
            $formatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message% in %context.file% on line %context.line%\n", "Y-m-d H:i:s", false, true);
            $handler = new StreamHandler(__DIR__ . '/Nono.log', log::DEBUG);
            $handler->setFormatter($formatter);
            self::$log->pushHandler($handler);
        }
    }

    public static function logError(string $message): void {
        if (self::$log === null) {
            throw new \RuntimeException("Logger is not initialized. Call Logger::init() first.");
        }

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $caller = $trace[1] ?? $trace[0] ?? ['file' => 'unknown', 'line' => 'unknown'];

        self::$log->error($message, [
            'file' => $caller['file'] ?? 'unknown',
            'line' => $caller['line'] ?? 'unknown',
        ]);
    }
}
