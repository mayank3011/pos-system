<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\BookGroup; // Import the BookGroup model
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // Using the correct driver
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function AllProduct()
    {
        $product = Product::latest()->get();
        return view('backend.product.all_product', compact('product'));
    } // End Method

    public function AddProduct()
    {
        $category = Category::latest()->get();
        $supplier = Supplier::latest()->get();
        $groups   = BookGroup::latest()->get(); // Retrieve available book groups
        return view('backend.product.add_product', compact('category', 'supplier', 'groups'));
    } // End Method 

    public function StoreProduct(Request $request)
    {
        // Generate product code
        $pcode = IdGenerator::generate(['table' => 'products', 'field' => 'product_code', 'length' => 4, 'prefix' => 'PC']);
        $image = $request->file('product_image');

        if ($image) {
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Process image using Intervention ImageManager
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image)->resize(300, 300);
            $img->save(public_path('upload/product/' . $name_gen));

            $save_url = 'upload/product/' . $name_gen;
        } else {
            $save_url = null;
        }

        Product::insert([
            'product_name'   => $request->product_name,
            'category_id'    => $request->category_id,
            'supplier_id'    => $request->supplier_id,
            'book_group_id'  => $request->book_group_id, // Save selected book group
            'product_code'   => $pcode,
            'product_garage' => $request->product_garage,
            'product_store'  => $request->product_store,
            'buying_date'    => $request->buying_date,
            'expire_date'    => $request->expire_date,
            'buying_price'   => $request->buying_price,
            'selling_price'  => $request->selling_price,
            'product_image'  => $save_url,
            'created_at'     => Carbon::now(),
        ]);

        return redirect()->route('all.product')->with([
            'message'    => 'Product Inserted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function EditProduct($id)
    {
        $product  = Product::findOrFail($id);
        $category = Category::latest()->get();
        $supplier = Supplier::latest()->get();
        $groups   = BookGroup::latest()->get(); // Get available book groups

        return view('backend.product.edit_product', compact('product', 'category', 'supplier', 'groups'));
    }

    public function UpdateProduct(Request $request)
    {
        $product_id = $request->id;

        if ($request->file('product_image')) {
            $image = $request->file('product_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Process image
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image)->resize(300, 300);
            $img->save(public_path('upload/product/' . $name_gen));

            $save_url = 'upload/product/' . $name_gen;

            // Remove old image if exists
            $old_image = Product::findOrFail($product_id)->product_image;
            if (file_exists(public_path($old_image)) && $old_image) {
                unlink(public_path($old_image));
            }

            Product::findOrFail($product_id)->update([
                'product_name'   => $request->product_name,
                'category_id'    => $request->category_id,
                'supplier_id'    => $request->supplier_id,
                'book_group_id'  => $request->book_group_id, // Update book group
                'product_code'   => $request->product_code,
                'product_garage' => $request->product_garage,
                'product_store'  => $request->product_store,
                'buying_date'    => $request->buying_date,
                'expire_date'    => $request->expire_date,
                'buying_price'   => $request->buying_price,
                'selling_price'  => $request->selling_price,
                'product_image'  => $save_url,
                'updated_at'     => Carbon::now(),
            ]);
        } else {
            Product::findOrFail($product_id)->update([
                'product_name'   => $request->product_name,
                'category_id'    => $request->category_id,
                'supplier_id'    => $request->supplier_id,
                'book_group_id'  => $request->book_group_id, // Update book group
                'product_code'   => $request->product_code,
                'product_garage' => $request->product_garage,
                'product_store'  => $request->product_store,
                'buying_date'    => $request->buying_date,
                'expire_date'    => $request->expire_date,
                'buying_price'   => $request->buying_price,
                'selling_price'  => $request->selling_price,
                'updated_at'     => Carbon::now(),
            ]);
        }

        return redirect()->route('all.product')->with([
            'message'    => 'Product Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function DeleteProduct($id)
    {
        $product = Product::findOrFail($id);

        // Remove product image if exists
        if (file_exists(public_path($product->product_image)) && $product->product_image) {
            unlink(public_path($product->product_image));
        }

        $product->delete();

        return redirect()->back()->with([
            'message'    => 'Product Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function BarcodeProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('backend.product.barcode_product', compact('product'));
    } // End Method 

    public function ImportProduct()
    {
        return view('backend.product.import_product');
    } // End Method 

    public function Export()
    {
        return Excel::download(new ProductExport, 'products.xlsx');
    } // End Method

    public function Import(Request $request)
    {
        $file = $request->file('import_file');

        // Check if file is uploaded
        if (!$file) {
            return redirect()->back()->with([
                'message'    => 'No file uploaded!',
                'alert-type' => 'error'
            ]);
        }

        // Validate file extension
        $allowedExtensions = ['xls', 'xlsx', 'csv'];
        $extension = $file->getClientOriginalExtension();

        if (!in_array($extension, $allowedExtensions)) {
            return redirect()->back()->with([
                'message'    => 'Invalid file type. Please upload an XLSX or CSV file.',
                'alert-type' => 'error'
            ]);
        }

        // Import the file
        Excel::import(new ProductImport, $file);

        return redirect()->back()->with([
            'message'    => 'Product Imported Successfully',
            'alert-type' => 'success'
        ]);
    } // End Method
}
