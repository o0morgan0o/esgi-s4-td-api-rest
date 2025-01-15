<?php

class ErrorHandler {

    public static function handleError(
      int $errno,
      string $errstr,
      string $errfile,
      int $errline
    ) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public static function handleException(Throwable $e) {
        // définition du code d'erreur
        http_response_code(500);
        // attention, en cas d'environnement de production, on ne voudrait pas montrer toutes ces infos
        echo json_encode([
          "code" => $e->getCode(),
          "message" => $e->getMessage(),
          "file" => $e->getFile(),
          "line" => $e->getLine()
        ]);
    }

}
