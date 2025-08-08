<?php

namespace App\Traits;

use App\Models\Association;

trait CountAssociationsMain
{
    public function countAssociationsMain()
    {
    $count = Association::count();

    return [
        'count' => $count,
        'message' => 'Association count retrieved successfully'
    ];
    }
}
