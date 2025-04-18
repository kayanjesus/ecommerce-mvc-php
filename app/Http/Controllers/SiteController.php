<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Produto;

class SiteController extends Controller
{
    public function index()
    {
        // return "index";
        $produtos = Produto::paginate(4);
        return view('home.index', compact('produtos'));
    }

    public function details($slug)
    {
        $produto = Produto::where('slug', $slug)->first(); // adiciona o firstOrFail
        return view('home.details', compact('produto'));
    }

    public function categoria($id)
    {
        $produtos = Produto::where('id_categoria', $id)->get(); // adiciona o firstOrFail
        return view('home.categoria', compact('produtos'));
    }

}
