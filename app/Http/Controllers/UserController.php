<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use DB;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('users.indexUser');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $objvalid=User::where('identificacion',$request['dni'])->get();
        
        if(!empty($objvalid[0])){
            return response()->json(['success'=>false],400);
        }else{
            $obj= new User();
            $obj->name=$request['name'];
            $obj->identificacion=$request['dni'];
            $obj->email=$request['email'];
            $obj->estado=1;
            $obj->password='$2y$10$odheLv9bS5EGTjxmIgFUmeaqy/GZrhT9UFn0lfUIpCX8tjc5Lo0ni';
            $obj->save();            
            return response()->json(['success'=>true,'user'=>$obj],201);
        }


        
    }

    /**     
     * @return []
     */
    public function listData()
    {        
            $aryPatient=User::all();
            $data=[]; $x=0;
            foreach($aryPatient as $k=> $objpt)
            {
                $data[$x]['id']=$objpt->id;
                $data[$x]['identificacion']=$objpt->identificacion;
                $data[$x]['name']=$objpt->name;
                $data[$x]['email']=$objpt->email;
                $data[$x]['estado']=$objpt->estado;
                $data[$x]['editable']=false;
                ++$x;            
            }
          return json_encode($data);        
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $obj=User::find($request['id']);
        $obj->name=$request['name'];
        $obj->save();
        return response()->json(['success'=>true],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            DB::transaction(function()use ($id){
                $obj=User::find($id);
                $obj->delete();
            });
            return response()->json(['success'=>true],201);

        } catch (\Exception $ex) {            
            return response()->json(['success'=>false,'error' => $ex->getMessage()],400);
        }


        
        
    }
}
