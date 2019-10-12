<?php

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Api for categories",
 *      description="",
 *      @OA\Contact(
 *          email="lovehardgame@yandex.ru"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */

namespace App\Http\Controllers;

use App\Category;

class CategoriesController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/categories",
     *      operationId="getAllCategories",
     *      summary="Receive list of all categories",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     * )
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'count' => $categories->count(),
            'categories' => $categories->toArray()
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/categories/{id}/products",
     *      operationId="getProductsByCategoryId",
     *      summary="Returns all products in selected category",
     *      @OA\Parameter(
     *          name="id",
     *          description="Category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=404, description="Category not found"),
     * )
     *
     */
    public function products($id)
    {
        $category = Category::findOrFail($id);
        return response()->json([
            'count' => $category->products->count(),
            'products' => $category->products
        ]);
    }
}
