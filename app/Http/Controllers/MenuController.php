<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\MenuItem;

class MenuController extends Controller
{
    public function getMenuItems()
    {
        $items = MenuItem::orderBy('id')->get()->toArray();

        $byId = [];
        foreach ($items as &$item) {
            $item['children'] = [];
            $byId[$item['id']] = &$item;
        }
        unset($item);

        $tree = [];
        foreach ($byId as &$item) {
            if (!is_null($item['parent_id']) && isset($byId[$item['parent_id']])) {
                $byId[$item['parent_id']]['children'][] = &$item;
            } else {
                $tree[] = &$item;
            }
        }
        unset($item);

        return $tree;
    }
}
