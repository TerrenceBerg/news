<?php

namespace App\Http\Controllers\News\Admin;

use App\Http\Controllers\Controller;
use App\Models\News\NewsUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::latest()->paginate(10);
        return view('vendor.news.admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        return view('vendor.news.admin.users.edit', compact('user'));
    }
}
