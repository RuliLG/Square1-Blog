<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paragraphs = $this->faker->paragraphs(rand(2, 6));
        $title = $this->faker->realText(50);
        $post = '';
        foreach ($paragraphs as $para) {
            $post .= '<p>' . $para . '</p>';
        }

        $adminUser = \App\Models\User::where('role', 'admin')->firstOrFail();

        return [
            'title' => $title,
            'published_at' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'description' => $post,
            'owner_id' => $adminUser->id,
        ];
    }
}
