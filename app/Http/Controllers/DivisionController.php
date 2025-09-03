<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Atleta;


class DivisionController extends Controller
{
    public function index()
    {
        $divisiones = Division::all();
        return view('catalogos.divisiones.index', compact('divisiones'));
    }

}
