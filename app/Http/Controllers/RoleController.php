<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\DB;



class RoleController extends Controller
{
    function createRole(Request $request){

        $role = new Role();
        $role->role = $request->role;
        $role->save();

        return response()->json([
            'message' => 'role added'
        ], 201);

    }

    function removeRole(Request $request){

        $roleId = $request->roleId;
        $role = DB::table('roles')
            ->where('id', $roleId);

        $role->delete();

        return response()->json([
            'message' => 'role removed'
        ], 201);
    }
}
