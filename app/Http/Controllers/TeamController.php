<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $team = User::whereNotNull('position')
            ->orderByRaw("
                CASE 
                    WHEN position LIKE '%Fundador%' THEN 1
                    WHEN position LIKE '%Editor%' THEN 2
                    WHEN position LIKE '%Gestor%' THEN 3
                    WHEN position LIKE '%Redator%' THEN 4
                    WHEN position LIKE '%Revisor%' THEN 5
                    WHEN position LIKE '%Social Media%' THEN 6
                    WHEN position LIKE '%Comunicação%' THEN 7
                    WHEN position LIKE '%Designer%' THEN 8
                    ELSE 9
                END
            ")
            ->orderBy('name')
            ->get()
            ->groupBy('position');

        return view('team', compact('team'));
    }
}
