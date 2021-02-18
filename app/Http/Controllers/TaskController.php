<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Omniphx\Forrest\Providers\Laravel\Facades\Forrest;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::where(['user_id' => Auth::user()->id])->paginate(5);
        $response = [
            'data' => $tasks,
            'pagination' => [
                'total' => $tasks->total(),
                'per_page' => $tasks->perPage(),
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'from' => $tasks->firstItem(),
                'to' => $tasks->lastItem()
            ]
        ];
        return response()->json($response,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [

            'name'        => 'required',
 
            'description' => 'required',
 
        ]);
 
        $task = Task::create([
 
            'name'        => request('name'),
 
            'description' => request('description'),
 
            'user_id'     => Auth::user()->id
 
        ]);
        $message = 'Success';
        //Store Todo object in SF
        try{

            Forrest::sobjects('Todo__c',[
                'method' => 'post',
                'body'   => [
                    'Name' => request('name'),
                    'Description__c' => request('description'),
                    'User_Id__c' => Auth::user()->id
                ]
            ]);
        }
        catch(Exception $e){
           throw new Exception('Error in Salesforce transaction');
        }

        return response()->json([
 
            'task'    => $task,
 
            'message' => $message
 
        ], 200);
 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->validate($request, [
            'name'        => 'required|max:255',
            'description' => 'required',
        ]);
        $task->name = request('name');
        $task->description = request('description');
        $task->save();
        return response()->json([
            'message' => 'Task updated successfully!'
        ], 200);
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json([
            'message' => 'Task deleted successfully!'
        ], 200);
    }
}
