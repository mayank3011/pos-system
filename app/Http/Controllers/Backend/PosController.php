<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Gloudemans\Shoppingcart\Facades\Cart;

class PosController extends Controller
{
    /**
     * Display the POS page with product list and category filter.
     */
    public function Pos(Request $request)
    {
        // Get filter inputs
        $category_id = $request->query('category_id');
        $group_id    = $request->query('group_id'); // New group filter

        // Build product query with relationships
        $query = Product::with(['category', 'group']);

        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        if ($group_id) {
            $query->where('book_group_id', $group_id);
        }

        $product = $query->get();

        // Get customers, categories, and groups for the filters
        $customer = Customer::latest()->get();
        $categories = \App\Models\Category::all();

        // If a category is selected, you might want to load only groups within that category.
        // Otherwise, load all groups.
        if ($category_id) {
            // Assuming groups are not directly linked to categories,
            // you can choose to load groups from products with that category:
            $groups = Product::where('category_id', $category_id)
                ->whereNotNull('book_group_id')
                ->with('group')
                ->get()
                ->pluck('group')
                ->unique('id');
        } else {
            $groups = \App\Models\BookGroup::all();
        }

        return view('backend.pos.pos_page', compact('product', 'customer', 'categories', 'groups'));
    }
    /**
     * Add a product to the cart.
     */
    public function AddCart(Request $request)
    {
        Cart::add([
            'id'      => $request->id,
            'name'    => $request->name,
            'qty'     => $request->qty,
            'price'   => $request->price,
            'weight'  => 20,
            'options' => []
        ]);

        $notification = array(
            'message'    => 'Product Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method 

    /**
     * Display all items in the cart.
     */
    public function AllItem()
    {
        $product_item = Cart::content();
        return view('backend.pos.text_item', compact('product_item'));
    } // End Method 

    /**
     * Update the quantity of a cart item.
     */
    public function CartUpdate(Request $request, $rowId)
    {
        $qty = $request->qty;
        Cart::update($rowId, $qty);

        $notification = array(
            'message'    => 'Cart Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method 

    /**
     * Remove an item from the cart.
     */
    public function CartRemove($rowId)
    {
        Cart::remove($rowId);

        $notification = array(
            'message'    => 'Cart Removed Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method 

    /**
     * Create an invoice for the cart.
     */
    public function CreateInvoice(Request $request)
    {
        $contents = Cart::content();
        $cust_id = $request->customer_id;
        $customer = Customer::where('id', $cust_id)->first();

        return view('backend.invoice.product_invoice', compact('contents', 'customer'));
    } // End Method 
}
