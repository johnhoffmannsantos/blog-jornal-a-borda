<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'total_posts' => $this->getTotalPosts($user),
            'my_posts' => $this->getMyPosts($user),
            'total_comments' => $this->getTotalComments($user),
            'total_categories' => Category::count(),
        ];

        $recentPosts = $this->getRecentPosts($user);
        $pendingComments = $this->getPendingComments($user);

        return view('admin.dashboard', compact('stats', 'recentPosts', 'pendingComments'));
    }

    private function getTotalPosts($user)
    {
        if ($user->isAdmin() || $user->isEditor()) {
            return Post::count();
        }
        return Post::where('author_id', $user->id)->count();
    }

    private function getMyPosts($user)
    {
        return Post::where('author_id', $user->id)->count();
    }

    private function getTotalComments($user)
    {
        if ($user->isAdmin() || $user->isEditor()) {
            return Comment::count();
        }
        return Comment::whereHas('post', function($query) use ($user) {
            $query->where('author_id', $user->id);
        })->count();
    }

    private function getRecentPosts($user)
    {
        $query = Post::with(['author', 'category'])->orderBy('created_at', 'desc');
        
        if (!$user->isAdmin() && !$user->isEditor()) {
            $query->where('author_id', $user->id);
        }
        
        return $query->limit(10)->get();
    }

    private function getPendingComments($user)
    {
        $query = Comment::with(['post'])->where('status', 'pending');
        
        if (!$user->isAdmin() && !$user->isEditor()) {
            $query->whereHas('post', function($q) use ($user) {
                $q->where('author_id', $user->id);
            });
        }
        
        return $query->limit(5)->get();
    }
}
