<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver; // ✅ Import the correct driver
// ✅ Correct Import for V3
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function AllEmployee()
    {
        $employee = Employee::latest()->get();
        return view('backend.employee.all_employee', compact('employee'));
    }

    public function StoreEmployee(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|max:200',
            'email' => 'required|unique:employees|max:200',
            'phone' => 'required|max:200',
            'address' => 'required|max:400',
            'salary' => 'required|max:200',
            'vacation' => 'required|max:200',
            'experience' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png',
            'city' => 'required',
        ],
        [
            'name.required' => 'Please input employee name',
            'email.required' => 'Please input employee email',
            'phone.required' => 'Please input employee phone',
            'address.required' => 'Please input employee address',
            'salary.required' => 'Please input employee salary',
            'vacation.required' => 'Please input employee vacation',
            'experience.required' => 'Please input employee experience',
            'image.required' => 'Please input employee image',
            'city.required' => 'Please input employee city',
        ]);

        // ✅ Check if an image is uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // ✅ Use ImageManager in V3 (No Facades\Image anymore)
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image)->resize(300, 300);
            $img->save(public_path('upload/employee/' . $name_gen));

            $save_url = 'upload/employee/' . $name_gen;
        } else {
            $save_url = null; // No image uploaded
        }

        Employee::create([
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

        return redirect()->route('all.employee')->with([
            'message' => 'Employee Inserted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function EditEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        return view('backend.employee.edit_employee', compact('employee'));
    }

    public function AddEmployee()
    {
        return view('backend.employee.add_employee');
    }
}
