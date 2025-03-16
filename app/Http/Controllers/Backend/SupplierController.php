<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // ✅ Import the correct driver
// ✅ Correct Import for V3
use Carbon\Carbon;

class SupplierController extends Controller
{
    public function AllSupplier()
    {
        $supplier = Supplier::latest()->get();
        return view('backend.supplier.all_supplier', compact('supplier'));
    }
    public function AddSupplier()
    {
        return view('backend.supplier.add_supplier');
    }
    public function EditSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('backend.supplier.edit_supplier', compact('supplier'));
    }

    public function StoreSupplier(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|max:200',
            'email' => 'required|unique:suppliers|max:200',
            'phone' => 'required|max:200',
            'address' => 'required|max:400',
            'shopname' => 'required|max:200',
            'account_holder' => 'required|max:200',
            'account_number' => 'required',
            'account_number' => 'required',
            'type' => 'required',
            'city' => 'required',
            'image' => 'required',
        ]);

        // ✅ Check if an image is uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // ✅ Use ImageManager in V3 (No Facades\Image anymore)
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image)->resize(300, 300);
            $img->save(public_path('upload/supplier/' . $name_gen));

            $save_url = 'upload/supplier/' . $name_gen;
        } else {
            $save_url = null; // No image uploaded
        }

        Supplier::insert([

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'shopname' => $request->shopname,
            'account_holder' => $request->account_holder,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'bank_branch' => $request->bank_branch,
            'type' => $request->type,
            'city' => $request->city,
            'image' => $save_url,
            'created_at' => Carbon::now(),

        ]);

        return redirect()->route('all.supplier')->with([
            'message' => 'Supplier Inserted Successfully',
            'alert-type' => 'success'
        ]);
    }
    public function DeleteSupplier($id)
    {
        $supplier_img = Supplier::findOrFail($id);
        $img = $supplier_img->image;
        unlink($img);

        Supplier::findOrFail($id)->delete();
        return redirect()->route('all.supplier')->with([
            'message' => 'Supplier Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
    public function UpdateSupplier(Request $request)
    {
        $supplier_id = $request->id;

        if ($request->file('image')) {

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image)->resize(300, 300);
            $img->save(public_path('upload/supplier/' . $name_gen));
            $save_url = 'upload/supplier/' . $name_gen;

            Supplier::findOrFail($supplier_id)->update([

                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'shopname' => $request->shopname,
                'account_holder' => $request->account_holder,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'type' => $request->type,
                'city' => $request->city,
                'image' => $save_url,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'supplier Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.supplier')->with($notification);
        } else {

            Supplier::findOrFail($supplier_id)->update([

                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'shopname' => $request->shopname,
                'account_holder' => $request->account_holder,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'type' => $request->type,
                'city' => $request->city,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'Supplier Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.supplier')->with($notification);
        } // End else Condition  
    }
    public function DetailsSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('backend.supplier.details_supplier', compact('supplier'));
    }
}

