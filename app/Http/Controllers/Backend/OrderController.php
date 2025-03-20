<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Orderdetails;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{


    public function FinalInvoice(Request $request)
    {
        // Ensure numeric values for total and pay
        $rtotal = floatval($request->total);
        $rpay = floatval($request->pay);

        if (!is_numeric($rtotal) || !is_numeric($rpay)) {
            return back()->with([
                'message' => 'Invalid total or payment amount!',
                'alert-type' => 'error'
            ]);
        }

        $mtotal = $rtotal - $rpay; // Safe subtraction

        // Prepare order data
        $orderData = [
            'customer_id' => $request->customer_id,
            'order_date' => $request->order_date,
            'order_status' => $request->order_status,
            'total_products' => $request->total_products,
            'sub_total' => $request->sub_total,
            'vat' => $request->vat,
            'invoice_no' => 'EPOS' . mt_rand(10000000, 99999999),
            'total' => $rtotal,
            'payment_status' => $request->payment_status,
            'pay' => $rpay,
            'due' => $mtotal,
            'created_at' => Carbon::now(),
        ];

        // Insert order and get the ID
        $order_id = Order::insertGetId($orderData);

        // Insert order details
        $contents = Cart::content();

        foreach ($contents as $content) {
            Orderdetails::create([
                'order_id' => $order_id,
                'product_id' => $content->id,
                'quantity' => $content->qty,
                'unitcost' => $content->price,
                'total' => $content->price * $content->qty, // Ensure correct total calculation
            ]);
        }

        // Clear cart
        Cart::destroy();

        return redirect()->route('dashboard')->with([
            'message' => 'Order Completed Successfully!',
            'alert-type' => 'success'
        ]);
    }



    public function PendingOrder()
    {

        $orders = Order::where('order_status', 'pending')->get();
        return view('backend.order.pending_order', compact('orders'));
    } // End Method 

    public function CompleteOrder()
    {

        $orders = Order::where('order_status', 'complete')->get();
        return view('backend.order.complete_order', compact('orders'));
    } // End Method 


    public function OrderDetails($order_id)
    {

        $order = Order::where('id', $order_id)->first();

        $orderItem = Orderdetails::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();
        return view('backend.order.order_details', compact('order', 'orderItem'));
    } // End Method 


    public function OrderStatusUpdate(Request $request)
    {

        $order_id = $request->id;

        $product = Orderdetails::where('order_id', $order_id)->get();
        foreach ($product as $item) {
            Product::where('id', $item->product_id)
                ->update(['product_store' => DB::raw('product_store-' . $item->quantity)]);
        }
        Order::findOrFail($order_id)->update(['order_status' => 'complete']);

        $notification = array(
            'message' => 'Order Done Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('pending.order')->with($notification);
    } // End Method 


    public function StockManage()
    {
        $product = Product::latest()->get();
        return view('backend.stock.all_stock', compact('product'));
    } // End Method 

    public function OrderInvoice($order_id){
         $order = Order::where('id',$order_id)->first();
        $orderItem = Orderdetails::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
        $pdf = Pdf::loadView('backend.order.order_invoice', compact('order','orderItem'))->setPaper('a4')->setOption([
                'tempDir' => public_path(),
                'chroot' => public_path(),
        ]);
         return $pdf->download('invoice.pdf');
    }// End Method 
    public function PendingDue(){
        $alldue = Order::where('due','>','0')->orderBy('id','DESC')->get();
        return view('backend.order.pending_due',compact('alldue'));
    } // End Method 
    public function OrderDueAjax($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order);
    } // End Method 

    public function UpdateDue(Request $request){


        $order_id = $request->id;
        $due_amount = $request->due;
        $pay_amount = $request->pay;
        $allorder = Order::findOrFail($order_id);
        $maindue = $allorder->due;
        $maindpay = $allorder->pay;
        $paid_due = $maindue - $due_amount;
        $paid_pay = $maindpay + $due_amount;
        Order::findOrFail($order_id)->update([
            'due' => $paid_due,
            'pay' => $paid_pay, 
        ]);
         $notification = array(
            'message' => 'Due Amount Updated Successfully',
            'alert-type' => 'success'
        ); 
        return redirect()->route('pending.due')->with($notification);
    }// End Method 


}
