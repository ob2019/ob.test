<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;

class CustomErrors
{
    private static $errors = [];

    public static function init()
    {
        self::$errors = Config::get('custom_errors.errors');
    }

    public static function getForJsonTesting(int $id, string $replace_from="", string $replace_to=""): array
    {
        if (!isset(self::$errors[$id])) {
            return [
                'code' => 0,
                'message' => self::$errors[0]
            ];
        }

        return [
            'code' => "{$id}",
            'message' => str_replace($replace_from, $replace_to, self::$errors[$id])
        ];
    }

    public static function getForValidator(int $id): string
    {
        if (!isset(self::$errors[$id])) {
            return "0|Unknown error";
        }

        return "{$id}|" . self::$errors[$id];
    }

    /**
     * Extract error code and error message from custom error message of format [error code]|[error_message]
     *
     * @param string $error
     * @return array
     */
    public static function getErrorPayload(string $error): array
    {
        $results = [];
        $data = explode("|", $error);

        if (count($data) == 2) {
            $results = [
                'code' => $data[0],
                'message' => $data[1],
            ];
        } else {
            $results = [
                'code' => 0,
                'message' => self::$errors[0]
            ];
        }

        return $results;
    }
}