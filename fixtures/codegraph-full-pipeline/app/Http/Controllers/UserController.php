<?php

namespace App\Http\Controllers;

use App\Services\UserService;

class UserController
{
    public function __construct(
        private UserService $service
    ) {}

    public function show()
    {
        $result = $this->service->find();

        return view('users.show', ['result' => $result]);
    }
}
