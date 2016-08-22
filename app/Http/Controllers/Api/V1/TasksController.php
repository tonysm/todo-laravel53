<?php

namespace App\Http\Controllers\Api\V1;

use App\Task;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class TasksController extends Controller
{
    /**
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request)
    {
        return $request->user()->tasks;
    }

    /**
     * @param Requests\Api\V1\CreateTask $request
     *
     * @return Task
     */
    public function store(Requests\Api\V1\CreateTask $request)
    {
        return $request->user()->newTask($request->all());
    }

    /**
     * @param Requests\Api\V1\UpdateTask $request
     * @param Task $task
     *
     * @return Task
     */
    public function update(Requests\Api\V1\UpdateTask $request, Task $task)
    {
        $task->update($request->all());

        return $task;
    }

    /**
     * @param Requests\Api\V1\DestroyTask $request
     * @param Task $task
     */
    public function destroy(Requests\Api\V1\DestroyTask $request, Task $task)
    {
        $task->delete();
    }
}
