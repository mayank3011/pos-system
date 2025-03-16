<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // ✅ Import the correct driver
// ✅ Correct Import for V3
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function AllCustomer()
    {
        $customer = customer::latest()->get();
        return view('backend.customer.all_customer', compact('customer'));
    }

    public function StoreCustomer(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|max:200',
            'email' => 'required|unique:customers|max:200',
            'phone' => 'required|max:200',
            'address' => 'required|max:400',
            'salary' => 'required|max:200',
            'vacation' => 'required|max:200',
        ]);

        // ✅ Check if an image is uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // ✅ Use ImageManager in V3 (No Facades\Image anymore)
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image)->resize(300, 300);
            $img->save(public_path('upload/customer/' . $name_gen));

            $save_url = 'upload/customer/' . $name_gen;
        } else {
            $save_url = null; // No image uploaded
        }

        customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'experience' => $request->experience,
            'salary' => $request->salary,
            'vacation' => $request->vacation,
            'city' => $request->city,
            'image' => $save_url,
            'created_at' => Carbon::now(),
        ]);

        return redirect()->route('all.customer')->with([
            'message' => 'customer Inserted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function AddCustomer()
    {
        return view('backend.customer.add_customer');
    }
    public function DeleteCustomer($id)
    {
        $customer_img = customer::findOrFail($id);
        $img = $customer_img->image;
        unlink($img);

        customer::findOrFail($id)->delete();

        $notification = array(
            'message' => 'customer Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method
    public function EditCustomer($id)
    {

        $customer = customer::findOrFail($id);
        return view('backend.customer.edit_customer', compact('customer'));
    } // End Method 
    public function UpdateCustomer(Request $request)
    {

        $customer_id = $request->id;

        if ($request->file('image')) {

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image)->resize(300, 300);
            $img->save(public_path('upload/customer/' . $name_gen));
            $save_url = 'upload/customer/' . $name_gen;

            customer::findOrFail($customer_id)->update([

                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'experience' => $request->experience,
                'salary' => $request->salary,
                'vacation' => $request->vacation,
                'city' => $request->city,
                'image' => $save_url,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'customer Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.customer')->with($notification);
        } else {

            customer::findOrFail($customer_id)->update([

                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'experience' => $request->experience,
                'salary' => $request->salary,
                'vacation' => $request->vacation,
                'city' => $request->city,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'customer Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.customer')->with($notification);
        } // End else Condition  


    } // End Method 

}
