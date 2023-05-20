<?php

namespace App\Base;

use Auth;
use App\Models\User;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected ?User $user;

    public function getCurrentUser(): ?User
    {
        return Auth::user();
    }
}
