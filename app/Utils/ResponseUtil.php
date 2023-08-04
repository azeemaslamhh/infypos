<?php

namespace App\Utils;

class ResponseUtil
{
    /**
     * @param $message
     * @param $data
     * @return array
     */
    public static function makeResponse($message, $data): array
    {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];
    }

    /**
     * @param $message
     * @param  array  $data
     * @return array
     */
    public static function makeError($message, array $data = []): array
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if (! empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }
}
