<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Comment::with(['post']);

        // Filtros baseados no role
        if (!$user->canManageAllPosts()) {
            $query->whereHas('post', function($q) use ($user) {
                $q->where('author_id', $user->id);
            });
        }

        // Filtro por status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Busca
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('author_name', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $comments = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function updateStatus(Request $request, Comment $comment)
    {
        $user = Auth::user();
        
        // Verificar permissão
        if (!$user->canManageAllPosts() && $comment->post->author_id !== $user->id) {
            abort(403, 'Você não tem permissão para modificar este comentário.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,spam'],
        ]);

        $comment->update(['status' => $validated['status']]);

        return back()->with('success', 'Status do comentário atualizado!');
    }

    public function destroy(Comment $comment)
    {
        $user = Auth::user();
        
        // Verificar permissão
        if (!$user->canManageAllPosts() && $comment->post->author_id !== $user->id) {
            abort(403, 'Você não tem permissão para excluir este comentário.');
        }

        $comment->delete();

        return back()->with('success', 'Comentário excluído com sucesso!');
    }
}
