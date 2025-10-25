<?php

declare(strict_types=1);

namespace App\System;

use PDO;

class QueryBuilderHelper
{

    public static function pageResponse(
        PDO $pdo,
        string $sqlBase,
        array $params,
        array $allowedFields,
        string $ordem = 'id:asc',
        string $alias = 't',
        ?callable $map = null,
        ?string $sqlCount = null,
        ?string $groupBy = null
    ): array {
        [$orderColumn, $flow] = array_pad(explode(':', $ordem, 2), 2, 'asc');
        $flow = (strtolower($flow) === 'desc') ? 'DESC' : 'ASC';

        if (!empty($allowedFields) && !in_array($orderColumn, $allowedFields, true)) {
            $orderColumn = 'id';
        }
        if (!preg_match('/^[A-Za-z0-9_]+$/', $orderColumn)) {
            $orderColumn = 'id';
        }

        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $itemsPerPage = isset($params['itemsPerPage']) ? (int)$params['itemsPerPage'] : 10;
        $offset = max(0, ($page - 1) * $itemsPerPage);

        $conditions = [];
        $binds = [];

        foreach ($params as $key => $value) {
            if (!in_array($key, $allowedFields, true) || $value === null) {
                continue;
            }

            $param = ':p_' . $key;

            if ($key === 'name') {
                $conditions[] = "{$alias}.{$key} LIKE {$param}";
                $binds[$param] = "%{$value}%";
            } else {
                $conditions[] = "{$alias}.{$key} = {$param}";
                $binds[$param] = $value;
            }
        }

        $whereClause = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
        $groupClause = $groupBy ? " GROUP BY {$groupBy}" : '';

        $sql = "{$sqlBase} {$whereClause} {$groupClause} ORDER BY {$alias}.{$orderColumn} {$flow} LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);

        foreach ($binds as $param => $value) {
            $stmt->bindValue($param, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($map) {
            $data = array_map($map, $data);
        }

        if ($sqlCount) {
            $countSql = $sqlCount . ' ' . $whereClause;
        } else {
            $countSql = "SELECT COUNT(*) as total FROM ({$sqlBase} {$whereClause}) as sub";
        }

        $stmtCount = $pdo->prepare($countSql);
        foreach ($binds as $param => $value) {
            $stmtCount->bindValue($param, $value, PDO::PARAM_STR);
        }
        $stmtCount->execute();

        $totalRow = $stmtCount->fetch(PDO::FETCH_ASSOC);
        $total = isset($totalRow['total']) ? (int)$totalRow['total'] : 0;

        return [
            'data' => $data,
            'total' => $total,
        ];
    }
}
