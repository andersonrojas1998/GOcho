<?php

namespace App\Http\Controllers;
use App\Model\task;
use Illuminate\Http\Request;

class taskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('task.indexTask');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $obj= new task();
            $obj->description=$request['description'];            
            $obj->date_finish=$request['date_finish'];
            $obj->status_id=1;
            $obj->user_id=\Auth::user()->id;            
            $obj->save();
            $obj['estado']="Activo";
            $obj['created_by']=\Auth::user()->name;
            return response()->json(['success'=>true,'task'=>$obj],201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
        $obj=task::find($request['id']);      
        $obj->description=$request['description'];
        $obj->save();
        return response()->json(['success'=>true],201);
    }
    public function listData($all,$status)
    {        

        $idUser=\Auth::user()->id;
        $bit=0;
        $sql=($all!=1)?" WHERE user_id=$idUser ":$bit=1;
        $conditional=($bit==0)? 'AND':'WHERE';
        $sql1=($status!=0 )? "  $conditional  status_id=$status ":"";

        $csql=($sql==1)? '':$sql;
            $aryPatient=\DB::SELECT(" SELECT task.id as idtask,task.description,task.date_finish,task.status_id,task.user_id, status.name as status,users.name as created_by from task 
                                        INNER JOIN  status on task.status_id=status.id 
                                        INNER JOIN users on task.user_id=users.id
                                         $csql   $sql1   ORDER BY  task.date_finish DESC" );
            $data=[]; $x=0;
            foreach($aryPatient as $k=> $objpt)
            {
                $data[$x]['id']=$objpt->idtask;
                $data[$x]['description']=$objpt->description;
                $data[$x]['date_finish']=$objpt->date_finish;
                $data[$x]['status']=$objpt->status_id;
                $data[$x]['estado']= $objpt->status;
                $data[$x]['editable']=false;
                $data[$x]['created_by']=$objpt->created_by;
                ++$x;            
            }
          return json_encode($data);        
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
            \DB::transaction(function()use ($id){
                $obj=task::find($id);
                $obj->delete();
            });
            return response()->json(['success'=>true],201);

        } catch (\Exception $ex) {            
            return response()->json(['success'=>false,'error' => $ex->getMessage()],400);
        }
    }
}

