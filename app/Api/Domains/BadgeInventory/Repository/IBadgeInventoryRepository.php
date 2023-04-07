<?php

namespace App\Api\Domains\BadgeInventory\Repository;

interface IBadgeInventoryRepository 
{
    function store($data);
    function list(array $fields = ['*']);
    function update(string $id, array $data = []);
    function delete(string $id);
    function find(string $id, array $fields = ['*']);
    function getWhere(array $where = [], array $fields = ["*"]);

}