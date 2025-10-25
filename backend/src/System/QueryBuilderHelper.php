<?php

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
        list($orderColumn, $flow) = explode(':', $ordem);
        $flow = strtolower($flow) === 'desc' ? 'DESC' : 'ASC';

        $page = isset($params['page']) ? (int) $params['page'] : 1;
        $itemsPerPage = isset($params['itemsPerPage']) ? (int) $params['itemsPerPage'] : 10;
        $offset = ($page - 1) * $itemsPerPage;

        $where = '';
        $binds = [];

        foreach ($params as $key => $value) {
            if (in_array($key, $allowedFields) && $value !== null) {
                $param = ":{$key}";
                $binds[$param] = $key === 'name' ? "%{$value}%" : $value;
                $where .= $key === 'name'
                    ? " AND {$alias}.{$key} LIKE {$param}"
                    : " AND {$alias}.{$key} = {$param}";
            }
        }

        $whereClause = $where ? ' WHERE ' . substr($where, 5) : '';
        $groupClause = $groupBy ? " GROUP BY {$groupBy}" : '';

        $sql = "$sqlBase {$whereClause} {$groupClause} ORDER BY {$alias}.{$orderColumn} {$flow} LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);

        foreach ($binds as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($map) {
            $data = array_map($map, $data);
        }

        if ($sqlCount) {
            $stmtCount = $pdo->prepare($sqlCount . ' ' . $whereClause);
        } else {
            $stmtCount = $pdo->prepare("SELECT COUNT(*) as total FROM ({$sqlBase} {$whereClause}) as sub");
        }

        foreach ($binds as $key => $value) {
            $stmtCount->bindValue($key, $value);
        }
        $stmtCount->execute();
        $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        return [
            'data' => $data,
            'total' => (int) $total,
        ];
    }
}
