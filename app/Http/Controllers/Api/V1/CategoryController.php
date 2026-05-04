<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CategoryFormRequest;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->successResponse(Category::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryFormRequest $request)
    {
        Gate::authorize('admin');
        $category = Category::create([
            'name' => $request->input('name'),
        ]);

        return $this->createdResponse($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryFormRequest $request, Category $category)
    {
        Gate::authorize('admin');
        $category->update($request->only(['name']));

        return $this->successResponse($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('admin');
        Category::findOrFail($id)->delete();

        return $this->noContentResponse();
    }

    public function categoryUse(Request $request)
    {
        Gate::authorize('admin');
        return Expense::selectRaw('categories.name as category, expenses.category_id, count(expenses.category_id) as total')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->groupBy('expenses.category_id', 'categories.name')
            ->get();
    }
}
