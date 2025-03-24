<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function AdminDestroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/logout')->with([
            'message' => 'Admin Logout Successfully',
            'alert-type' => 'info'
        ]);
    }

    public function AdminLogoutPage()
    {
        return view('admin.admin_logout');
    }

    public function AdminProfile()
    {
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view', compact('adminData'));
    }

    public function AdminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_image/' . $data->photo));
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_image'), $filename);
            $data['photo'] = $filename;
        }

        $data->save();

        return redirect()->back()->with([
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function ChangePassword()
    {
        return view('admin.change_password');
    }

    public function UpdatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with([
                'message' => 'Old Password Does not Match!',
                'alert-type' => 'error'
            ]);
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with([
            'message' => 'Password Changed Successfully',
            'alert-type' => 'success'
        ]);
    }

    // Admin User Management

    public function AllAdmin()
    {
        $alladminuser = User::latest()->get();
        return view('backend.admin.all_admin', compact('alladminuser'));
    }

    public function AddAdmin()
    {
        $roles = Role::all();
        return view('backend.admin.add_admin', compact('roles'));
    }

    public function StoreAdmin(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'roles' => 'nullable|exists:roles,id',
        ]);

        // Check if user already exists
        if (User::where('email', $request->email)->exists()) {
            return redirect()->back()->with([
                'message' => 'User with this email already exists!',
                'alert-type' => 'error'
            ]);
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Assign role if provided
        if ($request->roles) {
            $role = Role::find($request->roles);
            if ($role) {
                $user->assignRole($role->name);
            }
        }

        return redirect()->route('all.admin')->with([
            'message' => 'New Admin User Created Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function EditAdmin($id)
    {
        $roles = Role::all();
        $adminuser = User::findOrFail($id);
        return view('backend.admin.edit_admin', compact('roles', 'adminuser'));
    }

    public function UpdateAdmin(Request $request)
    {
        $admin_id = $request->id;
        $user = User::findOrFail($admin_id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        // Fix: Remove existing roles before assigning new ones
        $user->roles()->detach();

        if ($request->roles) {
            $role = Role::find($request->roles);
            if ($role) {
                $user->assignRole($role->name);
            }
        }

        return redirect()->route('all.admin')->with([
            'message' => 'Admin User Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function DeleteAdmin($id)
    {
        $user = User::findOrFail($id);
        if (!is_null($user)) {
            $user->delete();
        }

        return redirect()->back()->with([
            'message' => 'Admin User Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
    public function DownloadDatabase()
    {
        try {
            // Get all table names
            $tables = DB::select('SHOW TABLES');
            $databaseName = config('database.connections.mysql.database');

            $dump = "-- Database Backup - " . date('Y-m-d H:i:s') . "\n\n";

            foreach ($tables as $table) {
                $tableName = $table->{'Tables_in_' . $databaseName};

                // Add table structure
                $createTable = DB::select('SHOW CREATE TABLE ' . $tableName);
                $dump .= "\n\n" . $createTable[0]->{'Create Table'} . ";\n\n";

                // Add table data
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $rowData = array_map(function ($value) {
                        return is_string($value) ? "'" . addslashes($value) . "'" : $value;
                    }, (array)$row);

                    $dump .= "INSERT INTO $tableName (" . implode(', ', array_keys((array)$row)) . ") VALUES (" . implode(', ', $rowData) . ");\n";
                }
            }

            $fileName = $databaseName . '_backup_' . date('Y-m-d') . '.sql';

            return Response::make($dump, 200, [
                'Content-Type' => 'application/sql',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Backup failed: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }
    
}
