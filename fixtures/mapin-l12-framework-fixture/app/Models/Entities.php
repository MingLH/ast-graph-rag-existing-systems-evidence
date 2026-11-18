<?php

namespace App\Models;

class First {}

class Second
{
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
