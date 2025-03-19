<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DB;

class RoleController extends Controller
{
    public function AllPermission(){
        $permissions = Permission::all();
        return view('backend.pages.permission.all_permission',compact('permissions'));
    } // End Method
    public function AddPermission(){

        return view('backend.pages.permission.add_permission');
    } // End Method 

    public function StorePermission(Request $request){
        $role = Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);
        $notification = array(
            'message' => 'Permission Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.permission')->with($notification);
    }// End Method 
    public function EditPermission($id){
        $permission = Permission::findOrFail($id);
        return view('backend.pages.permission.edit_permission',compact('permission'));
    }// End Method 
    public function UpdatePermission(Request $request){
        $per_id = $request->id;
        Permission::findOrFail($per_id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);
        $notification = array(
            'message' => 'Permission Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.permission')->with($notification);
    }// End Method 
    public function DeletePermission($id){
        Permission::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Permission Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method 

    public function AllRoles(){
         $roles = Role::all();
        return view('backend.pages.roles.all_roles',compact('roles'));
    }// End Method 

    public function AddRoles(){
        return view('backend.pages.roles.add_roles');
    }// End Method 
    
     public function StoreRoles(Request $request){
        $role = Role::create([
            'name' => $request->name, 
        ]);
        $notification = array(
            'message' => 'Role Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.roles')->with($notification);
    }// End Method 

     public function EditRoles($id){
        $roles = Role::findOrFail($id);
        return view('backend.pages.roles.edit_roles',compact('roles'));
    }// End Method 

     public function UpdateRoles(Request $request){
        $role_id = $request->id;
        Role::findOrFail($role_id)->update([
            'name' => $request->name, 
        ]);
        $notification = array(
            'message' => 'Role Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles')->with($notification);
    }// End Method 

     public function DeleteRoles($id){
        Role::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Role Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method 

     public function AddRolesPermission(){
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('backend.pages.roles.add_roles_permission', compact('roles', 'permissions', 'permission_groups'));
    } // End Method 



    public function StoreRolesPermission(Request $request)
    {
        // Validate the request to ensure permissions are provided
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission' => 'required|array',
            'permission.*' => 'exists:permissions,id',
        ]);

        // Check if permissions exist before looping
        if (!empty($request->permission)) {
            $data = [];
            foreach ($request->permission as $item) {
                $data[] = [
                    'role_id' => $request->role_id,
                    'permission_id' => $item,
                ];
            }

            // Insert multiple rows in a single query for better performance
            DB::table('role_has_permissions')->insert($data);
        }

        // Success notification
        $notification = [
            'message' => 'Role Permission Added Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.roles.permission')->with($notification);
    }


    public function AllRolesPermission(){
        $roles = Role::all();
        return view('backend.pages.roles.all_roles_permission',compact('roles'));
    } // End Method  


    public function AdminEditRoles($id){
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('backend.pages.roles.edit_roles_permission',compact('role','permissions','permission_groups')); 

    } // End Method 


    public function RolePermissionUpdate(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Ensure that permissions are passed correctly
        if ($request->has('permission')) {
            $permissionNames = Permission::whereIn('id', $request->permission)->pluck('name')->toArray();
            $role->syncPermissions($permissionNames);
        } else {
            // If no permissions are selected, remove all existing permissions
            $role->syncPermissions([]);
        }

        $notification = [
            'message' => 'Role Permission Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.roles.permission')->with($notification);
    }



    public function AdminDeleteRoles($id){
        $role = Role::findOrFail($id);
        if (!is_null($role)) {
            $role->delete();
        }
        $notification = array(
            'message' => 'Role Permission Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method 
    
    public function AdminDeleteRoles($id){

        $role = Role::findOrFail($id);
        if (!is_null($role)) {
            $role->delete();
        }

        $notification = array(
            'message' => 'Role Permission Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method 
    


}

