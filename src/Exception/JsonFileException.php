<?php

declare(strict_types=1);

namespace BEAR\FakeJson\Exception;

use JsonException;
use RuntimeException;

use function sprintf;

final class JsonFileException extends RuntimeException
{
    public function __construct(string $jsonPath, JsonException $previous)
    {
        parent::__construct(
            sprintf('Failed to parse JSON file: %s', $jsonPath),
            0,
            $previous
        );
    }
}
