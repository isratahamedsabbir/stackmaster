<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Project::orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $url = asset($data->image && file_exists(public_path($data->image)) ? $data->image : 'default/logo.svg');
                    return '<img src="' . $url . '" alt="image" style="width: 50px; max-height: 100px; margin-left: 20px;">';
                })
                ->addColumn('status', function ($data) {
                    $backgroundColor = $data->status == "active" ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status == "active" ? '26px' : '2px';
                    
                    $status = '<div class="d-flex justify-content-center align-items-center">';
                    $status .= '<div class="form-check form-switch" style="position: relative; width: 50px; height: 24px; background-color: ' . $backgroundColor . '; border-radius: 12px; transition: background-color 0.3s ease; cursor: pointer;">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status" style="position: absolute; width: 100%; height: 100%; opacity: 0; z-index: 2; cursor: pointer;">';
                    $status .= '<span style="position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: transform 0.3s ease; transform: translateX('.$sliderTranslateX.');"></span>';
                    $status .= '<label for="customSwitch' . $data->id . '" class="form-check-label" style="margin-left: 10px;"></label>';
                    $status .= '</div>';
                    $status .= '</div>';
                
                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">

                                <a href="#" type="button" onclick="goToEdit(' . $data->id . ')" class="btn btn-primary fs-14 text-white delete-icn" title="Edit">
                                    <i class="fe fe-edit"></i>
                                </a>

                                <a href="#" type="button" onclick="goToOpen(' . $data->id . ')" class="btn btn-success fs-14 text-white delete-icn" title="Open">
                                    <i class="fe fe-eye"></i>
                                </a>

                                <a href="#" type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-trash"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['image' ,'status', 'action'])
                ->make();
        }
        return view("backend.layouts.project.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Project::where('status', 'active')->get();
        return view('backend.layouts.project.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'          => 'required|max:255',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file'          => 'nullable|file|mimes:pdf,doc,docx,txt,zip,rar,7z|max:2048',
            'description'   => 'nullable|string',
            'credintials'   => 'nullable|string',
            'technologies'  => 'nullable|string',
            'features'      => 'nullable|string',
            'note'          => 'nullable|string',
            'frontend'      => 'nullable|string|max:255',
            'backend'       => 'nullable|string|max:255',
            'github'        => 'nullable|string|max:255',
            'key'           => 'nullable|array',
            'value'         => 'nullable|array',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date',
            'type'          => 'nullable|in:personal,company,academic'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        try {
            $data = $validate->validated();
            
            if ($request->hasFile('image')) {
                $data['image'] = Helper::fileUpload($request->file('image'), 'project', time() . '_' . getFileName($request->file('image')));
            }

            if ($request->hasFile('file')) {
                $data['file'] = Helper::fileUpload($request->file('image'), 'project', time() . '_' . getFileName($request->file('file')));
            }

            $data['slug'] = Helper::makeSlug(Project::class, $data['name']);

            if ($request->has('key') && $request->has('value')) {

                $keys = $request->key;
                $values = $request->value;

                if (count($keys) !== count($values)) {
                    throw new Exception('Key and value must have the same count');
                }

                $metadata = [];
                foreach ($keys as $index => $key) {
                    $metadata[$key] = $values[$index];
                }
                $data['metadata'] = json_encode($metadata);

            }

            unset($data['key'], $data['value']);

            Project::create($data);

            session()->put('t-success', 'Project created successfully');
           
        } catch (Exception $e) {
            session()->put('t-error', $e->getMessage());
        }

        return redirect()->route('admin.project.index')->with('t-success', 'Project created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, $id)
    {
        $project = Project::findOrFail($id);
        return view('backend.layouts.project.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, $id)
    {
        $project = Project::findOrFail($id);
        $categories = Project::where('status', 'active')->get();
        return view('backend.layouts.project.edit', compact('project', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name'          => 'required|max:255',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file'          => 'nullable|file|mimes:pdf,doc,docx,txt,zip,rar,7z|max:2048',
            'description'   => 'nullable|string',
            'technologies'  => 'nullable|string',
            'credintials'   => 'nullable|string',
            'features'      => 'nullable|string',
            'note'          => 'nullable|string',
            'frontend'      => 'nullable|string|max:255',
            'backend'       => 'nullable|string|max:255',
            'github'        => 'nullable|string|max:255',
            'key'           => 'nullable|array',
            'value'         => 'nullable|array',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date',
            'type'          => 'nullable|in:personal,company,academic'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        try {

            $data = $validate->validated();
            $project = Project::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($project->image && file_exists(public_path($project->image))) {
                    Helper::fileDelete(public_path($project->image));
                }
                $data['image'] = Helper::fileUpload($request->file('image'), 'project', time() . '_' . getFileName($request->file('image')));
            }

            if ($request->hasFile('file')) {
                if ($project->file && file_exists(public_path($project->file))) {
                    Helper::fileDelete(public_path($project->file));
                }
                $data['file'] = Helper::fileUpload($request->file('file'), 'project', time() . '_' . getFileName($request->file('file')));
            }

            if ($request->has('key') && $request->has('value')) {
                $keys = $request->key;
                $values = $request->value;

                if (count($keys) !== count($values)) {
                    throw new Exception('Key and value must have the same count');
                }

                $metadata = [];
                foreach ($keys as $index => $key) {
                    $metadata[$key] = $values[$index];
                }
                $data['metadata'] = json_encode($metadata);
            }

            unset($data['key'], $data['value']);

            $project->update($data);
            session()->put('t-success', 'Project updated successfully');
        } catch (Exception $e) {
            session()->put('t-error', $e->getMessage());
        }

        return redirect()->route('admin.project.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = Project::findOrFail($id);
            if ($data->image && file_exists(public_path($data->image))) {
                Helper::fileDelete(public_path($data->image));
            }
            $data->delete();
            return response()->json([
                'status' => 't-success',
                'message' => 'Your action was successful!'
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 't-error',
                'message' => 'Your action was successful!'
            ]);
        }
    }

    public function status(int $id): JsonResponse
    {
        $data = Project::findOrFail($id);
        if (!$data) {
            return response()->json([
                'status' => 't-error',
                'message' => 'Item not found.',
            ]);
        }
        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();
        return response()->json([
            'status' => 't-success',
            'message' => 'Your action was successful!',
        ]);
    }

}
