<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View|Collection
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            return Task::all();
        }

        return view('tasks');
    }

    /**
     * Store a newly created task in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $rules = array(
            'title'       => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = ['errors' => $validator->getMessageBag()->toArray()];
        } else {
            // store
            $task = new Task();
            $task->title = $request->get('title');
            $task->save();

            $response = $task;
        }

        return Response::json($response);
    }

    /**
     * Update the specified task in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $task = Task::find($id);
        $task->completed = true;
        $task->save();
        return $task;
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();
        return $task;
    }
}
