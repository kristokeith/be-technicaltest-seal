<?php

namespace App\Repositories;

use App\Models\Project;

class ProjectRepository
{
    public function getProject($perPage = 10, $page = 1, $search = null, $sortBy = 'uuid', $sortDirection = 'asc')
    {
        $query = Project::query();

        if ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        }

        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
    public function getAll()
    {
        return Project::all();
    }

    public function getByUuid($uuid)
    {
        return Project::find($uuid);
    }

    public function getByName($name)
    {
        return Project::findByName($name);
    }

    public function store($request)
    {
        return Project::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    }

    public function update($request, $project)
    {
        $project->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return $project;
    }

    public function destroy($project)
    {
        return $project->delete();
    }
}
