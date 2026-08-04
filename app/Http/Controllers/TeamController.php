<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        // Busca todos os usuários ativos ordenados por hierarquia de cargo e nome
        $users = User::where('is_active', true)
            ->orderByRaw("
                CASE 
                    WHEN position LIKE '%Fundador%' OR position LIKE '%Fundadora%' THEN 1
                    WHEN position LIKE '%Editor%' OR position LIKE '%Editora%' THEN 2
                    WHEN position LIKE '%Gestor%' OR position LIKE '%Gestora%' THEN 3
                    WHEN position LIKE '%Redator%' OR position LIKE '%Redatora%' THEN 4
                    WHEN position LIKE '%Revisor%' OR position LIKE '%Revisora%' THEN 5
                    WHEN position LIKE '%Social Media%' THEN 6
                    WHEN position LIKE '%Comunicação%' THEN 7
                    WHEN position LIKE '%Designer%' THEN 8
                    ELSE 9
                END
            ")
            ->orderBy('name')
            ->get();

        // Agrupa primeiramente por Setor (department).
        // Se o usuário não tiver Setor preenchido, usa o Cargo (position) ou 'Colaboradores'
        $team = $users->groupBy(function ($user) {
            if (!empty($user->department)) {
                return $user->department;
            }
            if (!empty($user->position)) {
                return $user->position;
            }
            return 'Colaboradores';
        });

        return view('team', compact('team'));
    }
}