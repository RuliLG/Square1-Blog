<?php

namespace App\Utils;

class CacheKey {
    public static function post($id)
    {
        return 'post_' . $id;
    }

    public static function postList($isAscendingOrder, $page)
    {
        return 'posts_' . ($isAscendingOrder ? 'asc' : 'desc') . '_' . $page;
    }
}
