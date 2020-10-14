<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Auth;
use HasRoles;
use DataTables;

class UsersController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return void  
     */
    protected $__rulesforindex = ['username' => 'required', 'email' => 'required', 'created_at' => 'required'];
    protected $__rulesforindexadmin = ['first_name' => 'required', 'last_name' => 'required', 'email' => 'required'];

    public function index(Request $request) {
        $keyword = $request->get('search');
        $perPage = 15;

        if (!empty($keyword)) {
            $users = User::where('id', '!=', Auth::id())->where('name', 'LIKE', "%$keyword%")->orWhere('email', 'LIKE', "%$keyword%")
                            ->latest()->paginate($perPage);
        } else {
            $users = User::where('id', '!=', Auth::id())->latest()->paginate($perPage);
        }

        return view('admin.users.index', compact('users'));
    }

    public function indexByRoleId(Request $request, $role_id) {
//        $keyword = $request->get('search');
//        $perPage = 5;
//
//        $roleusers = \DB::table('role_user')->where('role_id', $role_id)->pluck('user_id');
//        if (!empty($keyword)) {
//            $users = User::wherein('id', $roleusers)->where('firstname', 'LIKE', "%$keyword%")->orWhere('email', 'LIKE', "%$keyword%")->latest()->paginate($perPage);
//        } else {
//            $users = User::wherein('id', $roleusers)->latest()->paginate($perPage);
//        }
////        dd($role_id);
//        return view('admin.users.index', compact('users', 'role_id'));


        if ($request->ajax()) {
            $roleusers = \DB::table('role_user')->where('role_id', $role_id)->pluck('user_id');
            $users = User::wherein('id', $roleusers)->latest();
            return Datatables::of($users)
                            ->addIndexColumn()
                            ->addColumn('action', function($item)use($role_id) {
//                                $return = 'return confirm("Confirm delete?")';
                                $return = '';
                                if ($role_id == '1') {
                                    $return .= " <a href=" . url('/admin/users/' . $item->id . '/edit') . " title='Edit User'><button class='btn btn-primary btn-sm'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button></a>";
                                }
//                                if ($role_id != '1'):
                                if ($item->state == '1'):
                                    $return .= "&nbsp;<button class='btn btn-success btn-sm changeStatus' title='Unblock'  data-id=" . $item->id . " data-status='UnBlock'><i class='fa fa-unlock' aria-hidden='true'></i></button>";
                                else:
                                    $return .= "&nbsp;<button class='btn btn-danger btn-sm changeStatus' title='Block' data-id=" . $item->id . " data-status='Block' ><i class='fa fa-ban' aria-hidden='true'></i></button>";
                                endif;
//                                endif;
                                $return .= " <a href=" . url('/admin/users/' . $item->id) . " title='View Player'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>";
//                                $return .= " <a href=" . url('/admin/playerfriends/' . $item->id) . " title='Player friends'><button class='btn btn-warning btn-sm'><i class='fa fa-users' aria-hidden='true'></i></button></a>";
                                if ($role_id != '1') {
                                    $return .= "&nbsp;<button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/users/' . $item->id) . "'><i class='fa fa-trash-o' aria-hidden='true'></i> </button>";
                                }
                                return $return;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
        }
        if ($role_id == '1') {
            return view('admin.users.index', ['rules' => array_keys($this->__rulesforindexadmin), 'role_id' => $role_id]);
        } else {
            return view('admin.users.index', ['rules' => array_keys($this->__rulesforindex), 'role_id' => $role_id]);
        }
//        return view('admin.users.index', ['rules' => array_keys($this->__rulesforindex), 'role_id' => $role_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create($role_id = null) {
        $roles = Role::select('id', 'name', 'label')->get();
        $roles = $roles->pluck('label', 'name');

        return view('admin.users.create', compact('roles', 'role_id'));
    }

    public function store(Request $request) {
        $this->validate(
                $request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => 'required',
                ]
        );

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['status'] = '1';
        $user = User::create($data);
        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }
        return redirect(url()->previous())->with('flash_message', 'user Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id) {
        $user = User::findOrFail($id);

        if ($user->hasRole('Super-Admin')):
            return view('admin.users.show.superadmin', compact('user'));
        else:
            return view('admin.users.show.customer', compact('user'));
        endif;
    }

    public function showPlayerFriends($user_id) {
//        dd($user_id);
        $playerfriends = new \App\UserFriend();
        $playerfriends = $playerfriends->select('id', 'user_id', 'friend_id', 'status', 'params', 'state');
        $playerfriends = $playerfriends->where(function($query) use ($user_id) {
            $query->where('user_id', $user_id);
            $query->orWhere('friend_id', $user_id);
        });
        $playerfriends = $playerfriends->where("status", "accepted");
        return view('admin.users.show.showplayerfriend', compact('playerfriends'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
//    public function edit($id) {
//        $roles = Role::select('id', 'name', 'label')->get();
//        $roles = $roles->pluck('label', 'name');
//
//        $user = User::with('roles')->select('id', 'first_name','last_name', 'email')->findOrFail($id);
//        $user_roles = [];
//        foreach ($user->roles as $role) {
//            $user_roles[] = $role->name;
//        }
//
//        return view('admin.users.edit', compact('user', 'roles', 'user_roles', 'role_id'));
//    }
//    public function update(Request $request, $id) {
//        $this->validate(
//                $request, [
//            'first_name' => 'required',
//            'last_name' => 'required',
//            'email' => 'required|string|max:255|email|unique:users,email,' . $id,
//                ]
//        );
//
//        $data = $request->except('password');
//        if ($request->has('password')) {
//            if (!empty($request->password))
//                $data['password'] = bcrypt($request->password);
//        }
//
//        $user = User::findOrFail($id);
//        $user->update($data);
//
//        return redirect('admin/users/' . $id)->with('flash_message', 'User updated!');
//    }

    public function edit($id) {
        $roles = Role::select('id', 'name', 'label')->get();
        $roles = $roles->pluck('label', 'name');
        $user = User::with('roles')->select('id', 'first_name', 'last_name', 'email', 'password')->findOrFail($id);
        $user_roles = [];
        foreach ($user->roles as $role) {
            $user_roles[] = $role->name;
        }

        return view('admin.users.edit', compact('user', 'roles', 'user_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return void
     */
    public function update(Request $request, $id) {
        $this->validate(
                $request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|max:255|email|unique:users,email,' . $id,
            'roles' => 'required'
                ]
        );
        $data = $request->except('password');
// if ($request->has('password')) {
// $data['password'] = bcrypt($request->password);
// }
        $user = User::findOrFail($id);
// dd($user->toArray());
        $user->update($data);
        $user->roles()->detach();
        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }
// dd($user->roles->toArray());
// $role_id = \DB::table('role_user')->select('role_id')->get();
        $role_id = $user->roles->first()->id;
// dd($role_id);
        return redirect('admin/users/role/' . $role_id)->with('flash_message', 'User Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
//    public function destroy($id) {
//        User::destroy($id);
//        return redirect('admin/users')->with('flash_message', 'User deleted!');
//    }
    public function destroy($id) {
       if (User::destroy($id)) {
           $data = 'Success';
       } else {
           $data = 'Failed';
       }
       return response()->json($data);
   }

    public function changeStatus(Request $request) {
        $user = User::findOrFail($request->id);
        $user->state = $request->status == 'Block' ? '1' : '0';
        $user->save();
        return response()->json(["success" => true, 'message' => 'User updated!']);
    }

}
