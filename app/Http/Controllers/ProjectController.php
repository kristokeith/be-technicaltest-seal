<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProjectRepository;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    protected $projectRepository;
    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->middleware('permission:project-index', ['only' => ['index', 'show']]);
        $this->middleware('permission:project-add', ['only' => ['store']]);
        $this->middleware('permission:project-edit', ['only' => ['update']]);
        $this->middleware('permission:project-delete', ['only' => ['destroy']]);
    }
    /**
     * @OA\Get(
     *     path="/api/projects",
     *     tags={"Project"},
     *     summary="Get all projects with optional sorting, searching, and pagination",
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
     *         description="Field to sort by (uuid or name)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"uuid", "name", "description", "created_at", "updated_at"},
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
     *                 description="project list",
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

        $project = $this->projectRepository->getproject($limit, $page, $search, $sortBy, $sortDirection);

        return response()->json([
            'status' => true,
            'message' => 'project list',
            'data' => $project,
        ], 200);
    }
    /**
     * @OA\Get(
     *     path="/api/projects/{uuid}",
     *     tags={"Project"},
     *     summary="Get project by index",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="Project UUID",
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
        $project = Project::findOrFail($uuid);
        return response()->json($project);
    }
    /**
     * @OA\Post(
     *     path="/api/projects",
     *     tags={"Project"},
     *     summary="Store New Project",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Project name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         required=true,
     *         description="Project description",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Project Created Successfully"
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
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400);
        }
        $project = $this->projectRepository->store($request);
        return response()->json($project, 201);
    }
    /**
     * @OA\Put(
     *     path="/api/projects/{uuid}",
     *     tags={"Project"},
     *     summary="Update project",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="Project UUID",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Project name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         required=true,
     *         description="Project description",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Project not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request - Invalid data provided"
     *     ),
     * )
     */
    public function update(Request $request, $uuid)
    {
        $project = $this->projectRepository->getByUuid($uuid);
        if (!$project) {
            return response()->json([
                'status' => false,
                'message' => 'project not found',
            ], 404);
        }

        $rules = [
            'name' => 'required|string',
            'description' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $this->projectRepository->update($request, $project);

        return response()->json([
            'status' => true,
            'message' => 'Project updated',
        ], 200);
    }
    /**
     * @OA\Delete(
     *     path="/api/projects/{uuid}",
     *     tags={"Project"},
     *     summary="Delete project",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="Project UUID",
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
     *         description="Project not found"
     *     ),
     * )
     */
    public function destroy($uuid)
    {
        $project = $this->projectRepository->getByUuid($uuid);

        if (!$project) {
            return response()->json([
                'status' => false,
                'message' => 'Project not found',
            ], 404);
        }

        $this->projectRepository->destroy($project);

        return response()->json([
            'status' => true,
            'message' => 'Project deleted',
        ], 200);
    }
}
