<?php

namespace Tuna976\NEWS\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Tuna976\NEWS\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::latest()->paginate(10);
        return view('news::news.admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        return view('news::news.admin.users.edit', compact('user'));
    }
}
