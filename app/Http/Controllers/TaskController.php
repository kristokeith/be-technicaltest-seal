<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    protected $taskRepository;
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->middleware('permission:task-index', ['only' => ['index', 'show', 'showByProject']]);
        $this->middleware('permission:task-add', ['only' => ['store']]);
        $this->middleware('permission:task-edit', ['only' => ['update']]);
        $this->middleware('permission:task-delete', ['only' => ['destroy']]);
    }
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Get all tasks with optional sorting, searching, and pagination",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=10
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search keyword for UUID or name",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         description="Field to sort by",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"uuid", "project_uuid", "name", "description", "due_date", "created_at", "updated_at"},
     *             default="uuid"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sortDirection",
     *         in="query",
     *         description="Sort direction (asc or desc)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"asc", "desc"},
     *             default="asc"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 description="Status of the request"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Response message"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Task list",
     *                 @OA\Property(
     *                     property="current_page",
     *                     type="integer",
     *                     description="Current page number"
     *                 ),
     *                 @OA\Property(
     *                     property="first_page_url",
     *                     type="string",
     *                     description="URL of the first page"
     *                 ),
     *                 @OA\Property(
     *                     property="from",
     *                     type="integer",
     *                     description="Index of the first item in the current page"
     *                 ),
     *                 @OA\Property(
     *                     property="last_page",
     *                     type="integer",
     *                     description="Last page number"
     *                 ),
     *                 @OA\Property(
     *                     property="last_page_url",
     *                     type="string",
     *                     description="URL of the last page"
     *                 ),
     *                 @OA\Property(
     *                     property="next_page_url",
     *                     type="string",
     *                     description="URL of the next page"
     *                 ),
     *                 @OA\Property(
     *                     property="path",
     *                     type="string",
     *                     description="URL path of the current request"
     *                 ),
     *                 @OA\Property(
     *                     property="per_page",
     *                     type="integer",
     *                     description="Number of items per page"
     *                 ),
     *                 @OA\Property(
     *                     property="prev_page_url",
     *                     type="string",
     *                     description="URL of the previous page"
     *                 ),
     *                 @OA\Property(
     *                     property="to",
     *                     type="integer",
     *                     description="Index of the last item in the current page"
     *                 ),
     *                 @OA\Property(
     *                     property="total",
     *                     type="integer",
     *                     description="Total number of items"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $limit = $request->input('perPage', 10);
        $page = $request->input('page', 1);
        $search = $request->input('search');
        $sortBy = $request->input('sortBy', 'uuid');
        $sortDirection = $request->input('sortDirection', 'asc');

        $tasks = $this->taskRepository->getTasks($limit, $page, $search, $sortBy, $sortDirection);
        return response()->json([
            'status' => true,
            'message' => 'Task list',
            'data' => $tasks,
        ], 200);
    }
    /**
     * @OA\Get(
     *     path="/api/tasks/{uuid}",
     *     tags={"Tasks"},
     *     summary="Get Task by uuid",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="Task UUID",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function show($uuid)
    {
        $task = $this->taskRepository->getByUuid($uuid);
        return response()->json([
            'status' => true,
            'message' => 'Task detail',
            'data' => $task,
        ], 200);
    }
    /**
     * @OA\Get(
     *     path="/api/tasks/project/{project_uuid}",
     *     tags={"Tasks"},
     *     summary="Get task by Project UUID",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="project_uuid",
     *         in="path",
     *         required=true,
     *         description="Project UUID",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Successful operation"
     *     )
     * )
     */
    public function showByProject($project_uuid)
    {
        $task = $this->taskRepository->getByProject($project_uuid);
        return response()->json([
            'status' => true,
            'message' => 'Task detail',
            'data' => $task,
        ], 200);
    }
    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Store New Task",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Task name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         required=true,
     *         description="Task description",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="project_uuid",
     *         in="query",
     *         required=true,
     *         description="UUID of the project the task belongs to",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="due_date",
     *         in="query",
     *         required=true,
     *         description="Task due date in YYYY-MM-DD HH:MM:SS format",
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Task Created Successfully"
     *     ),
     *     @OA\Response(
     *       response="400",
     *       description="Bad request - Invalid data provided"
     *     ),
     *     @OA\Response(
     *       response="500",
     *       description="Internal Server Error"
     *     )
     * )
     */

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:tasks,name',
            'description' => 'required',
            'project_uuid' => 'required',
            'due_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $task = $this->taskRepository->create($request);
        return response()->json([
            'status' => true,
            'message' => 'Task created',
            'data' => $task,
        ], 201);
    }
    /**
     * @OA\Put(
     *     path="/api/tasks/{uuid}",
     *     tags={"Tasks"},
     *     summary="Update task",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="Task UUID",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Task name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         required=true,
     *         description="Task description",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="project_uuid",
     *         in="query",
     *         required=true,
     *         description="UUID of the project the task belongs to",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="due_date",
     *         in="query",
     *         required=true,
     *         description="Task due date in YYYY-MM-DD HH:MM:SS format",
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request - Invalid data provided"
     *     ),
     * )
     */

    public function update(Request $request, $uuid)
    {
        $task = $this->taskRepository->getByUuid($uuid);
        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Task not found',
            ], 404);
        }

        $rules = [
            'name' => 'required|unique:tasks,name,' . $task->uuid . ',uuid',
            'description' => 'required',
            'project_uuid' => 'required',
            'due_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $this->taskRepository->update($request, $task);

        return response()->json([
            'status' => true,
            'message' => 'Task updated',
            'data' => $task,
        ], 200);
    }
    /**
     * @OA\Delete(
     *     path="/api/tasks/{uuid}",
     *     tags={"Tasks"},
     *     summary="Delete task",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="Task UUID",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     * )
     */
    public function destroy($uuid)
    {
        $task = $this->taskRepository->getByUuid($uuid);
        if (!$task) {
            return response()->json([
                'status' => false,
                'message' => 'Task not found',
            ], 404);
        }
        $this->taskRepository->delete($task);
        return response()->json([
            'status' => true,
            'message' => 'Task deleted',
        ], 200);
    }
}
