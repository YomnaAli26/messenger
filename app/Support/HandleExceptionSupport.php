<?php
namespace App\Support;
use App\Exceptions\ExceptionHandler;

use Illuminate\Http\Response;

class HandleExceptionSupport
{
    /**
     * @throws ExceptionHandler
     */
    public static function notFound(string $message)
    {
        throw new ExceptionHandler($message,Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws ExceptionHandler
     */
    public static function notFoundIf(bool $condition, string $message)
    {
        if ($condition) {
            self::notFound($message);

        }

    }

    /**
     * @throws ExceptionHandler
     */
    public static function badRequest(string $message)
    {
        throw new ExceptionHandler($message,Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws ExceptionHandler
     */
    public static function badRequestIf(bool $condition, string $message)
    {
        if ($condition) {
            self::badRequest($message);
        }
    }


    /**
     * @throws ExceptionHandler
     */
    public static function unprocessableEntity(string $message)
    {
        throw new ExceptionHandler($message,Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public static function unprocessableEntityIf(bool $condition,string $message)
    {
        if ($condition) {
            self::unprocessableEntity($message);
        }


    }

    /**
     * @throws ExceptionHandler
     */
    public static function forbidden(string $message)
    {
        throw new ExceptionHandler($message,Response::HTTP_FORBIDDEN);
    }
    public static function forbiddenIf(bool $condition, string $message)
    {
        if ($condition) {
            self::forbidden($message);
        }
    }

    /**
     * @throws ExceptionHandler
     */
    public static function unauthorized(string $message)
    {
        throw new ExceptionHandler($message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @throws ExceptionHandler
     */
    public static function info(string $message)
    {
        throw new ExceptionHandler($message, Response::HTTP_OK);
    }

    /**
     * @throws ExceptionHandler
     */
    public static function throwException(string $message)
    {
        throw new ExceptionHandler($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }


}
