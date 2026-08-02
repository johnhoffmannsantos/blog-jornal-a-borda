<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        // 1. Busca absolutamente TODOS os usuários cadastrados
        $users = User::orderBy('name')->get();

        // 2. Agrupa por 'department' (Setor). Se estiver nulo ou vazio, atribui 'Equipe Jornalística'
        $team = $users->groupBy(function ($user) {
            $dept = trim((string) $user->department);
            return !empty($dept) ? $dept : 'Equipe Jornalística';
        });

        return view('team', compact('team'));
    }
}