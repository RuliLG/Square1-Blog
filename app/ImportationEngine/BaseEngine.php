<?php

namespace App\ImportationEngine;

use App\Exceptions\ImportationError;
use App\Models\BlogPost;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class BaseEngine {
    public function __construct(private string $url)
    {
        // ...
    }

    /**
     * Runs the importation engine
     *
     * @return int number of posts imported
     */
    public function run(): int
    {
        // First of all, we need to get the data from the source.
        $rawPosts = $this->request();

        // Then, we will iterate over the results and store the new blog posts.
        // We understand something as a "new blog post" if its publication date is newer than the last one.
        $lastPost = BlogPost::fromAdmin()->fromApi()->latest()->first();
        $postsToInsert = [];
        foreach ($rawPosts as $rawPost) {
            $post = $this->getPostFromRawData($rawPost);
            if ($lastPost && $post['published_at']->isAfter($lastPost->published_at) || !$lastPost) {
                $post['created_at'] = $post['published_at'];
                $post['updated_at'] = $post['published_at'];
                $post['origin'] = 'api';
                $postsToInsert[] = $post;
            }
        }

        BlogPost::insert($postsToInsert);
        return count($postsToInsert);
    }

    /**
     * Performs an HTTP request to the source and returns the posts array.
     * @return array
     */
    protected function request(): array
    {
        $response = Http::get($this->url);
        $response->throw();

        $data = $response->json();

        // Once we have the data, we will check if we need to fetch any special index from the received JSON
        // If so, we will try to call a method named "getPostsIndex"

        if ($this->getPostsIndex() !== null) {
            $data = Arr::get($data, $this->getPostsIndex());
        }

        // If there's no response, then we will just throw an error
        if (blank($data)) {
            Log::error('No data received from the source.', [
                'url' => $this->url,
                'status_code' => $response->status(),
                'response' => $response->json(),
            ]);

            throw new ImportationError('No data received from the source', 422);
        }

        return $data;
    }

    /**
     * Converts an array to an array containing the BlogPost fields
     *
     * @param array $rawPost
     * @return array
     */
    protected abstract function getPostFromRawData(array $data): array;

    /**
     * Defines the index of the JSON response that contains the posts.
     * @returns string|null
     */
    protected function getPostsIndex(): ?string
    {
        return null;
    }
}
