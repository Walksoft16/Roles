<?php

namespace Walksoft\Roles\Http\Controllers;

use RolesAppController as Controller;

//use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ultraware\Roles\Models\Role;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;
use Ultraware\Roles\Models\Permission;
use Auth;

class RolesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){

        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        if(!Auth::user()->hasPermission('list.roles') &&
           !Auth::user()->hasPermission('create.roles') &&
           !Auth::user()->hasPermission('edit.roles') &&
           !Auth::user()->hasPermission('delete.roles')){
          Session::flash('error',trans('adminlte::adminlte.app_msj_not_permissions'));
          return redirect()->route('admin');
        }

        $permits = [];
        $permits['create'] = Auth::user()->hasPermission('create.roles');
        $permits['edit']   = Auth::user()->hasPermission('edit.roles');
        $permits['delete'] = Auth::user()->hasPermission('delete.roles');

        $roles = Role::orderBy('created_at','DESC')->get();
        return  view('Roles::admin.roles.index',compact('roles','permits'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){

        if(!Auth::user()->hasPermission('create.roles')){
          Session::flash('error',trans('adminlte::adminlte.app_msj_not_permissions'));
          return redirect()->route('admin');
        }
        return view('Roles::admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        if(!Auth::user()->hasPermission('create.roles')){
          Session::flash('error',trans('adminlte::adminlte.app_msj_not_permissions'));
          return redirect()->route('admin');
        }

        $this->validate($request,[
            'name' => 'required|string|max:255|unique:roles',
            'state' => 'required|max:1|numeric'
        ]);

        try{
            //Creating Rol
            $Role = Role::create([
                'name' => $request->name,
                'slug' => strtolower(str_replace(' ','.',$request->name)),
                'description' => $request->description,
                'level' => $request->state,
            ]);

            if($request->permits && count($request->permits) > 0){
                set_time_limit(0);
                foreach($request->permits as $slug => $name){

                    $Permission = Permission::where('slug',$slug)->first();

                    if(!$Permission){

                        $Permission = Permission::create([
                            'name' => $name,
                            'slug' => $slug,
                            'description' => 'Permits for '.$name
                        ]);
                    }//if

                    $Role->attachPermission($Permission);

                }//foreach

            }//if

            Session::flash('success',trans('Roles::roles.role_successfully_created'));
        }catch(QueryException $e){
            Session::flash('error','error');
        }//cath

        return redirect()->route('roles.index');

    }//store

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){

        if(!Auth::user()->hasPermission('delete.roles')){
          Session::flash('error',trans('adminlte::adminlte.app_msj_not_permissions'));
          return redirect()->route('admin');
        }

        $role = Role::find($id);
        $Permission=Permission::select(['permissions.slug'])->join('permission_role','permission_role.permission_id','=','permissions.id')->join('roles','roles.id','=','permission_role.role_id')->where('roles.id',$role->id)->pluck('slug')->toArray();
        return view('Roles::admin.roles.show',compact('role','Permission'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        if(!Auth::user()->hasPermission('delete.roles')){
          Session::flash('error',trans('adminlte::adminlte.app_msj_not_permissions'));
          return redirect()->route('admin');
        }

        try{
            $role = Role::find($id);
            $role->delete();

            Session::flash('success', trans('Roles::roles.role_successfully_delete'));
        } catch (QueryException $e) {
            Session::flash('error', 'error');
        }//cath

        return redirect()->route('roles.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){

        if(!Auth::user()->hasPermission('edit.roles')){
          Session::flash('error',trans('adminlte::adminlte.app_msj_not_permissions'));
          return redirect()->route('admin');
        }

        $role = Role::find($id);
        $Permission=Permission::select(['permissions.slug'])->join('permission_role','permission_role.permission_id','=','permissions.id')->join('roles','roles.id','=','permission_role.role_id')->where('roles.id',$role->id)->pluck('slug')->toArray();
        return view('Roles::admin.roles.edit',compact('role','Permission'));

    }

    /**
     *
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id){

        if(!Auth::user()->hasPermission('edit.roles')){
          Session::flash('error',trans('adminlte::adminlte.app_msj_not_permissions'));
          return redirect()->route('admin');
        }

        try{
            $role = Role::find($id);

            $this->validate($request,[
                'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
                'state' => 'required|max:1|numeric'
            ]);

            $role->name = $request->name;
            $role->slug = strtolower(str_replace(' ','.',$request->name));
            $role->description = $request->description;
            $role->level = $request->state;
            $role->save();

            $Permissions = [];

            if($request->permits && count($request->permits)>0){
                set_time_limit(0);
                $Permission=Permission::select(['permissions.slug'])->join('permission_role','permission_role.permission_id','=','permissions.id')->join('roles','roles.id','=','permission_role.role_id')->where('roles.id',$role->id)->pluck('slug')->toArray();
                foreach($request->permits as $slug => $name){
                    $Permission = Permission::where('slug',$slug)->first();
                    if(!$Permission){
                        $Permission = Permission::create([
                            'name' => $name,
                            'slug' => $slug,
                            'description' => 'Permits for '.$name
                        ]);
                    }
                    $Permissions[] = $Permission->id;
                }//foreach
                $role->syncPermissions($Permissions);
            }//if
            else{
                $role->detachAllPermissions();
            }//else

            Session::flash('success',trans('Roles::roles.role_successfully_updated'));

        }catch(QueryException $e){
            Session::flash('error','error');
        }//cath

        return redirect()->route('roles.edit',['id' => $role->id]);
    }
}
