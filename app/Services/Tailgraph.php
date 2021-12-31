<?php

namespace App\Services;

class Tailgraph {
    static function url($title, $description)
    {
        return 'https://og.tailgraph.com/og?titleTailwind=text-4xl%20font-extrabold%20text-center%20text-black&textTailwind=mt-4%20text-xl%20text-gray-60%20text-center%20w-full%20font-medium&footer=' . config('app.url') . '&footerTailwind=text-base%20mb-8%20text-center%20font-semibold%20text-indigo-600&containerTailwind=max-w-2xl&bgTailwind=bg-white&title=' . urlencode($title) . '&text=' . urlencode($description);
    }
}
