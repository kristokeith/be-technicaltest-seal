<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function getTasks($perPage = 10, $page = 1, $search = null, $sortBy = 'uuid', $sortDirection = 'asc')
    {
        $query = Task::query();

        $query->leftJoin('projects', 'tasks.project_uuid', '=', 'projects.uuid')
            ->select('tasks.*', 'projects.name as project_name');

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('tasks.name', 'like', "%$search%")
                    ->orWhere('tasks.description', 'like', "%$search%")
                    ->orWhere('projects.name', 'like', "%$search%");
            });
        }

        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function getAll()
    {
        return Task::all();
    }

    public function getByUuid($uuid)
    {
        return Task::find($uuid);
    }

    public function getByName($name)
    {
        return Task::where('name', $name)->get();
    }

    public function getByProject($project_uuid)
    {
        return Task::where('project_uuid', $project_uuid)->get();
    }

    public function create($request)
    {
        return Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'project_uuid' => $request->project_uuid,
            'due_date' => $request->due_date,
        ]);
    }

    public function update($request, $task)
    {
        return $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'project_uuid' => $request->project_uuid,
            'due_date' => $request->due_date,
        ]);
    }

    public function delete($task)
    {
        return $task->delete();
    }
}
