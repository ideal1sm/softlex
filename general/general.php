<?php
require_once '../vendor/autoload.php';

$array = [
    ["id" => 1, "date" => "12.01.2020", "name" => "test1"],
    ["id" => 2, "date" => "02.05.2020", "name" => "test2"],
    ["id" => 4, "date" => "08.03.2020", "name" => "test4"],
    ["id" => 1, "date" => "22.01.2020", "name" => "test1"],
    ["id" => 2, "date" => "11.11.2020", "name" => "test4"],
    ["id" => 3, "date" => "06.06.2020", "name" => "test3"],
];

# 1. выделить уникальные записи (убрать дубли) в отдельный массив. в конечном массиве не должно быть элементов с одинаковым id.
# Плюс написал второй вариант сортировки
function uniqueValues(array $data): array
{
    usort($data, function ($a, $b) {
        return ($a['id'] - $b['id']);
    });
    $result = array_filter($data, function ($object, $key) use ($data) {
        if ($key !== 0) {
            return !($object['id'] === $data[$key - 1]['id']);
        }
        return $object;
    }, ARRAY_FILTER_USE_BOTH);

    return array_values($result);
}

$first = uniqueValues($array);

# 2. Отсортировать многомерный массив по ключу (любому)

function sortArray(array $data): array
{
    array_multisort(array_column($data, 'id'), $data);

    return $data;
}

$second = sortArray($array);

# 3. Вернуть из массива только элементы, удовлетворяющие внешним условиям (например элементы с определенным id)

function getFilteredData(array $data, int $id): array
{
    $result = array_filter($data, function ($object) use ($id) {
        return $object['id'] === $id;
    });

    return array_values($result);

}

$third = getFilteredData($array, 3);

# 4. Изменить в массиве значения и ключи (использовать name => id в качестве пары ключ => значение)

function swapKeysAnsValues(array $data): array
{
    return array_combine(array_column($data, 'name'), array_column($data, 'id'));
}

$fourth = swapKeysAnsValues($array);

dump(compact('first', 'second', 'third', 'fourth'));

# 5.

//SELECT g.id, g.name
//FROM goods g
//WHERE NOT EXISTS (
//    SELECT t.id
//  FROM tags t
//  WHERE NOT EXISTS (
//    SELECT gt.tag_id
//    FROM goods_tags gt
//    WHERE gt.tag_id = t.id AND gt.goods_id = g.id
//  )
//);

# 6.

//SELECT department_id
//FROM evaluations
//where gender = true
//GROUP BY department_id
//having sum(IF(value > 5, 1, 0)) = count(value)

