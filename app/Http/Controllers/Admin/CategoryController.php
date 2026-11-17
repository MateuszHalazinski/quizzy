<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    
    public function index()
    {
    $categories = Category::withCount('quizzes')->orderBy('name')->get();

    return view('admin.categories.index', compact('categories'));
    }

   
    public function create()
    {
        return view('admin.categories.create', ['category' => new Category]);
    }

    public function store(Request $request)
    {
    $data = $request->validate(['name' => ['required', 'string', 'max:100', 'unique:categories,name'],
    ]);

    Category::create($data);

    return redirect()
        ->route('admin.categories.index')
        ->with('success', 'Kategoria została dodana.');
    }

    /*
     
    public function show(Category $category)
    {
        //
    }
    */
   
    public function edit(Category $category)
    {
    return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:100', Rule::unique('categories')->ignore($category)],
    ]);

    $category->update($data);

    return redirect()
        ->route('admin.categories.index')
        ->with('success', 'Zmiany zostały zapisane.');
    }

    public function destroy(Category $category)
    {
    if ($category->quizzes()->exists()) {
        return back()->with('error', 'Nie można usunąć kategorii, która ma przypisane quizy.');
    }

    $category->delete();

    return redirect()
        ->route('admin.categories.index')
        ->with('success', 'Kategoria została usunięta.');
    }
}
