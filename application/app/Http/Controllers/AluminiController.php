<?php

namespace App\Http\Controllers;

use App\Alumini;
use App\User;
use Illuminate\Http\Request;
use Session;
use DB;
use Auth;  

class AluminiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alumini = Alumini::get();
        return view('admin.alumini.index',compact('alumini'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::get();
        $alumini = Alumini::first();
        return view('admin.alumini.create',compact('users','alumini'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAluminiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $this->validate($request,[
            'user_id' => 'required',
            
        ]);
        $users = Alumini::where('user_id',$request->user_id)->count();
        if($users >0){
            Session::flash('success',trans('User Already Exist'));

        } else {
            $input = $request->all();
            $input['status'] = isset($request->status) ? 1 : 0;
            $input['user_id'] = $request->user_id;
            $input['url'] = $request->url;
            $alumini = Alumini::create($input); 
            $alumini->save();
            Session::flash('success',trans('Create Successfully'));
        }

        return redirect('alumini');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Alumini  $alumini
     * @return \Illuminate\Http\Response
     */
    public function show(Alumini $alumini)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Alumini  $alumini
     * @return \Illuminate\Http\Response
     */
    public function edit(){
        $users = User::get();
        $data = Alumini::first();
        return view('admin.alumini.edit',compact('users','data'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAluminiRequest  $request
     * @param  \App\Alumini  $alumini
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $alumini = Alumini::findOrFail($id);
        $input = $request->all();
        $input['status'] = isset($request->status) ? 1 : 0;
        $input['user_id'] = $request->user_id;
        $input['role_id'] = $request->role_id;
        $alumini->update($input);
        Session::flash('success', trans('flash.UpdatedSuccessfully'));
        return redirect('alumini');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Alumini  $alumini
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('aluminis')->where('id',$id)->delete();
        Session::flash('delete', trans('flash.DeletedSuccessfully'));
        return redirect('alumini');
    }
    public function front(){
        // return $id;
        $id = Auth::User()->id;
        $alumini = Alumini::where('user_id',$id)->first();
        return view('front.alumini',compact('alumini'));
    }
    public function footer(){
     $alumini = Alumini::where('status','1')->get();
    return view('front.alumini.footer',compact('alumini'));

    }
    public function frontupdate(Request $request,$user_id){
        $alumini = Alumini::where('user_id',$user_id)->first();
        $input = $request->all();
        $input['status'] = isset($request->status) ? 1 : 0;
        $input['url'] = $request->url;
        $alumini->update($input);
        Session::flash('success', trans('flash.UpdatedSuccessfully'));
        return redirect('front/alumini');
    }
    public function footerupdate(Request $request,$id){
    
        $alumini = Alumini::where('id',$id)->first();
        return view('front.alumini.view',compact('alumini'));
    }
}
