<?php
namespace App\Http\Controllers\SuperAdminDashboard\EShop;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
     protected $fileuploadService;
    private $filePath = 'adminAsset/product_image/';
    public function __construct(FileUploadService $file_upload_service)
    {
        $this->fileuploadService = $file_upload_service;
    }
    public function index(Request $request)
    {
        $products = Product::query();
        if ($request->filled('shop_category_id')) {
            $products = $products->where('shop_category_id', $request->shop_category_id);
        }
        if ($request->filled('product_name')) {
            $products = $products->where('product_name', "LIKE", '%' . $request->product_name . "%");
        }
        $products = $products->latest('id')->paginate($request->per_page??10);
        return response()->json($products);
    }

    public function store(ProductRequest $request)
    {
        $product                   = new Product();
        $product->shop_category_id = $request->shop_category_id;
        $product->product_name     = $request->product_name;
        $product->product_link     = $request->product_link;
        $product->product_details  = $request->product_details;

        if ($request->hasFile('product_image') && $request->file('product_image')->isValid()) {
               $product->product_image = $this->fileuploadService->setPath($this->filePath)->saveOptimizedImage($request->file('product_image'), 40, 1320, null, true);
        }
        $product->save();

        return response()->json([
            'message' => 'Category added Successfully',
            'data'    => $product,
        ]);
    }

    public function show(string $id)
    {
        $product = Product::find($id);
        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json(['data' => $product]);
    }

    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->shop_category_id = $request->shop_category_id ?? $product->shop_category_id;
        $product->product_name     = $request->product_name ?? $product->product_name;
        $product->product_link     = $request->product_link ?? $product->product_link;
        $product->product_details  = $request->product_details ?? $product->product_details;

        if ($request->hasFile('product_image') && $request->file('product_image')->isValid()) {
            if (! empty($product->product_image)) {
                removeImage($product->product_image);
            }
            $product->product_image = saveImage($request, 'product_image');
        }

        $product->save();

        return response()->json([
            'message' => 'Product updated successfully',
            'data'    => $product,
        ]);
    }

    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
