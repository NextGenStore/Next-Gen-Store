<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsRequest;
use App\Http\Resources\ProductListResource;
use App\Models\Products;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search', false);
        $perPage = request('per_page', 10);
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');
        $query = Products::query();
        $query->orderBy($sortField, $sortDirection);
        if ($search) {
            $query->where('name', 'like', '%{$search}%')
                ->orWhere('description', 'like', '%{$search}%');
        }

        return ProductListResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductsRequest $request)
    {

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        /** @var UploadedFile $image */
        $image = $data['image'] ?? null;

        if ($image){
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
        }

        $product = Products::create($data);

        return new ProductListResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $products)
    {
        return new ProductListResource($products);
    }

    public function update(ProductsRequest $request, Products $products)
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        /** @var \Illuminate\Http\UploadedFile $image */

        $image = $data['image'] ?? null;

        if ($image){
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));

            if ($products->image){
                Storage::deleteDirectory( '/public/' .dirname($products->image));
            }
        }

        $products->update($data);
        return new ProductListResource($products);


    }


    /**
     * Update the specified resource in storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Products $products)
    {
        $products->delete();

        return response()->noContent();
    }

    private function saveImage(UploadedFile $image): string
    {
        $path = 'images/' . Str::random();
        if (!Storage::exists($path)){
            Storage::makeDirectory($path , 0755, true);
        }
        if (!Storage::putFileAs('public/' .$path , $image , $image->getClientOriginalName())){
            throw new \Exception("Failed to save image \"{$image->getClientOriginalName()}\"");
        }
        return $path . '/' . $image->getClientOriginalName();
    }
}
