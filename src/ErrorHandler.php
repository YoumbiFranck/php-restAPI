<?php
class ErrorHandler
{
    public static function handleException(Throwable $exception): void
    {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            'error' => [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]
        ]);
    }

    public static function handleError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ): bool 
    {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }    

}