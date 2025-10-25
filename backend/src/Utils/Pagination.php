<?php

namespace App\Utils;

class Pagination
{

    public static function getParams(array $params): array
    {
        $page = isset($params['page']) && $params['page'] > 0 ? (int)$params['page'] : 1;
        $itemsPerPage = isset($params['itemsPerPage']) ? (int)$params['itemsPerPage'] : 10;

        $offset = ($page - 1) * $itemsPerPage;

        $return = ['page' => $page, 'itemsPerPage' => $itemsPerPage, 'offset' => $offset,];

        if ($params) {
            foreach ($params as $key => $value) {
                if (!in_array($key, ['page', 'itemsPerPage', 'offset']) && $value !== null) {
                    $return[$key] = $value;
                }
            }
        }

        return $return;
    }

    public static function formatResponse(array $data, int $total): array
    {
        return [ 'data' => $data, 'total' => $total,];
    }
}
