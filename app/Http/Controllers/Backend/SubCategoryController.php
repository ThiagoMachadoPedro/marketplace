<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\SubCategoria;
use Illuminate\Http\Request;
use Str;


class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $categorias = SubCategoria::where('name', 'like', '%' . $search . '%')->paginate(7);
        } else {
            $categorias = SubCategoria::paginate(7);
        }

        return view('admin.sub-category.index', compact('categorias'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categorias = Categoria::all();

        return view('admin.sub-category.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // Método para armazenar a nova categoria
    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $request->validate([
            'name' => 'required|string|max:255',
            'id_categoria' => ['required', 'max:200', 'unique:sub_categoria,name'],
            'status' => 'required',
        ]);


        $subCategoria = new SubCategoria();

        $subCategoria->id_categoria = $request->id_categoria;
        $subCategoria->name = $request->name;
        $subCategoria->status = $request->status;
        $subCategoria->slug = Str::slug($request->name);

        $subCategoria->save();

        // dd($request->all());

        // Redirecionar para a lista de categorias com uma mensagem de sucesso
        return redirect()->route('subcategoria.index')->with('success', 'Sub-Categoria criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categoria = SubCategoria::findOrFail($id);
        $categorias = Categoria::all(); // Buscar todas as categorias

        return view('admin.sub-category.edit', compact('categoria', 'categorias'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'id_categoria' => ['required', 'max:200', 'unique:sub_categoria,name'],
            'status' => 'required|boolean',
        ]);

        $categoria = SubCategoria::findOrFail($id);

        // Atualiza os campos da categoria com os dados do request
        $categoria->name = $request->name;
        $categoria->id_categoria = $request->id_categoria;
        $categoria->status = $request->status;
        $categoria->slug = Str::slug($request->name);

        // Salva as alterações no banco de dados
        $categoria->save();  // Salva a instância existente

        // Redirecionar com uma mensagem de sucesso
        return redirect()->route('subcategoria.index')->with('success', 'Sub-Categoria atualizada com sucesso!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoria = SubCategoria::findOrFail($id);

        // Excluir a categoria do banco de dados
        $categoria->delete();

        return redirect()->route('subcategoria.index')->with('success', 'Sub-Categoria removida com sucesso!');
    }

    public function toggleStatus(Request $request)
    {
        $subCategoria = SubCategoria::find($request->id);

        if ($subCategoria) {
            $subCategoria->status = $request->status; // Atualiza o status com o valor vindo da requisição
            $subCategoria->save(); // Salva a alteração no banco

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }


}
