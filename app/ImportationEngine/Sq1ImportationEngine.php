<?php

namespace App\ImportationEngine;

use App\Models\BlogPost;

class Sq1ImportationEngine extends BaseEngine {
    public function __construct()
    {
        parent::__construct(config('import.sq1'));
    }

    protected function getPostFromRawData(array $data): array
    {
        return [
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'published_at' => \Carbon\Carbon::parse($data['publication_date']),
        ];
    }

    protected function getPostsIndex(): ?string
    {
        return 'data';
    }
}
