<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use function request;

class ProductController extends Controller
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(): Factory|View|Application
    {
        $query = Products::query();

        return $this->renderProducts($query);
    }

    public function view(Products $product): View|Application
    {
        return view('product.view', ['product' => $product]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function renderProducts(Builder $query): View|Application|Factory
    {
        $search = request()->get('search');
        $sort = request()->get('sort', '-updated_at');

        if ($sort) {
            $sortDirection = 'asc';
            if ($sort[0] === '-') {
                $sortDirection = 'desc';
            }
            $sortField = preg_replace('/^-?/', '', $sort);

            $query->orderBy($sortField, $sortDirection);
        }
        $products = $query
            ->where('published', '=', 1)
            ->where(function ($query) use ($search) {
                /** @var $query Builder */
                $query->where('products.title', 'like', "%$search%")
                    ->orWhere('products.description', 'like', "%$search%");
            })
            ->paginate(5);

        return view('product.index', [
            'products' => $products
        ]);
    }

}
