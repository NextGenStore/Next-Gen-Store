<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductVendorRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductVendorResource;
use App\Models\Products;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class VendorController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        return view('vendor.products.create');
    }
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
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
     * @throws Exception
     */
    public function store(ProductVendorRequest $request): ProductVendorResource
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        /** @var UploadedFile $image */
        $image = $data['image'] ?? null;

        if ($image){
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();
        }

        $product = Products::create($data);

        return new ProductVendorResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): ProductVendorResource
    {
        $product = Products::find($id);
        return new ProductVendorResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        $product = Products::find($id);
        return view('vendor.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     * @throws Exception
     */
    public function update(Request $request, string $id): ProductVendorResource
    {
        $product = (new \App\Models\Products)->getRouteKeyName($id);
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        /** @var UploadedFile|null $image */
        $image = $data['image'] ?? null;

        if ($image) {
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();
        }

        $product->update($data);

        return new ProductVendorResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(): Response
    {
//        $products->delete();

        return response()->noContent();
    }

    /**
     * @throws Exception
     */
    private function saveImage(UploadedFile $image): string
    {
        $path = 'images/' . Str::random();
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0755, true);
        }
        if (!Storage::putFileAS('public/' . $path, $image, $image->getClientOriginalName())) {
            throw new Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
        }

        return $path . '/' . $image->getClientOriginalName();
    }
}
