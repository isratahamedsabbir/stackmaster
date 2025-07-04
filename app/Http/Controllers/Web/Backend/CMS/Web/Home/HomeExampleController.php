<?php

namespace App\Http\Controllers\Web\Backend\CMS\Web\Home;

use App\Http\Controllers\Controller;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\CmsRequest;
use App\Services\CmsService;
use Illuminate\Support\Str;

class HomeExampleController extends Controller
{
    protected $cmsService;

    
    public $page;
    public $component;
    public $section;
    
    public $components;
    public $sections;
    public $count;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
        
        $this->page = PageEnum::HOME;

        $this->component = ['title', 'sub_title', 'bg', 'image', 'description', 'btn_text', 'btn_color', 'btn_link'];
        $this->section = SectionEnum::EXAMPLE;

        $this->sections = SectionEnum::EXAMPLES;
        $this->components = ['title', 'sub_title', 'bg', 'image', 'description', 'sub_description', 'btn_text', 'btn_link', 'btn_color', 'metadata'];
        $this->count = 10;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = CMS::where('page', $this->page)->where('section', $this->sections)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    if ($data->image) {
                        $url = asset($data->image);
                        return '<img src="' . $url . '" alt="image" width="50px" height="50px" style="margin-left:20px;">';
                    } else {
                        return '<span>No Image Available</span>';
                    }
                })
                ->addColumn('status', function ($data) {
                    $backgroundColor = $data->status == "active" ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status == "active" ? '26px' : '2px';
                    $sliderStyles = "position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: transform 0.3s ease; transform: translateX($sliderTranslateX);";

                    $status = '<div class="form-check form-switch" style="margin-left:40px; position: relative; width: 50px; height: 24px; background-color: ' . $backgroundColor . '; border-radius: 12px; transition: background-color 0.3s ease; cursor: pointer;">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status" style="position: absolute; width: 100%; height: 100%; opacity: 0; z-index: 2; cursor: pointer;">';
                    $status .= '<span style="' . $sliderStyles . '"></span>';
                    $status .= '<label for="customSwitch' . $data->id . '" class="form-check-label" style="margin-left: 10px;"></label>';
                    $status .= '</div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <a href="#" onClick="editItem(' . $data->id . ')" type="button" class="btn btn-primary fs-14 text-white edit-icn" title="Edit">
                                    <i class="fe fe-edit"></i>
                                </a>

                                <a href="#" onClick="goToShow(' . $data->id . ')" type="button" class="btn btn-info fs-14 text-white edit-icn" title="View">
                                    <i class="fe fe-eye"></i>
                                </a>

                                <a href="#" type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-trash"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make();
        }

        $data = CMS::where('page', $this->page)->where('section', $this->section)->latest()->first();
        return view("backend.layouts.cms.index", ["data" => $data, "page" => $this->page->value, "section" => $this->section->value, "component" => $this->component, 'sections' => $this->sections]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("backend.layouts.cms.create", ["page" => $this->page->value, "section" => $this->section->value, "components" => $this->components]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CmsRequest $request)
    {
        $validatedData = $request->validated();

        try {
            // Add the page and section to validated data
            $validatedData['page'] = $this->page;
            $validatedData['section'] = $this->sections;

            $counting = CMS::where('page', $validatedData['page'])->where('section', $validatedData['section'])->count();
            if ($counting >= $this->count) {
                return redirect()->back()->with('t-error', "Maximum  {$this->count} Item You Can Add");
            }

            if ($request->hasFile('bg')) {
                $validatedData['bg'] = Helper::fileUpload($request->file('bg'), $this->section->value, time() . '_' . getFileName($request->file('bg')));
            }

            if ($request->hasFile('image')) {
                $validatedData['image'] = Helper::fileUpload($request->file('image'), $this->section->value, time() . '_' . getFileName($request->file('image')));
            }

            do {
                $validatedData['slug'] = 'slug_'.Str::random(8);
            } while (CMS::where('slug', $validatedData['slug'])->exists());

            // Create or update the CMS entry
            if ($request->has('rating')) {
                $validatedData['metadata']['rating'] = $validatedData['rating'];
                unset($validatedData['rating']);
            }

            // Create or update the CMS entry
            CMS::create($validatedData);

            return redirect()->route("admin.cms.{$this->page->value}.{$this->section->value}.index")->with('t-success', 'Created successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = CMS::where('id', $id)->first();
        return view("backend.layouts.cms.show", ["data" => $data, "page" => $this->page->value, "section" => $this->section->value]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = CMS::findOrFail($id);
        return view("backend.layouts.cms.update", ["data" => $data, "page" => $this->page->value, "section" => $this->section->value, "components" => $this->components]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CmsRequest $request, $id)
    {
        $validatedData = $request->validated();

        try {
            // Find the existing CMS record by ID
            $section = CMS::findOrFail($id);

            // Update the page and section if necessary
            $validatedData['page'] = $this->page;
            $validatedData['section'] = $this->sections;

            if ($request->hasFile('bg')) {
                if ($section->bg && file_exists(public_path($section->bg))) {
                    Helper::fileDelete(public_path($section->bg));
                }
                $validatedData['bg'] = Helper::fileUpload($request->file('bg'), $this->section->value, time() . '_' . getFileName($request->file('bg')));
            }

            if ($request->hasFile('image')) {
                if ($section->image && file_exists(public_path($section->image))) {
                    Helper::fileDelete(public_path($section->image));
                }
                $validatedData['image'] = Helper::fileUpload($request->file('image'), $this->section->value, time() . '_' . getFileName($request->file('image')));
            }

            // Update the meta data
            if ($request->has('rating')) {
                $validatedData['metadata']['rating'] = $validatedData['rating'];
                unset($validatedData['rating']);
            }

            // Update the CMS entry with the validated data
            $section->update($validatedData);

            return redirect()->route("admin.cms.{$this->page->value}.{$this->section->value}.index")->with('t-success', 'Updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->cmsService->destroy($id);

            return response()->json([
                't-success' => true,
                'message' => 'Deleted successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                't-success' => false,
                'message' => 'Failed to delete.',
            ]);
        }
    }

    public function status(int $id): JsonResponse
    {
        try {
            $this->cmsService->status($id);
            return response()->json([
                't-success' => true,
                'message' => 'Updated successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                't-success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function content(CmsRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $validatedData['page'] = $this->page;
            $validatedData['section'] = $this->section;
            $section = CMS::where('page', $this->page)->where('section', $this->section)->first();

            if ($request->hasFile('bg')) {
                if ($section && $section->bg && file_exists(public_path($section->bg))) {
                    Helper::fileDelete(public_path($section->bg));
                }
                $validatedData['bg'] = Helper::fileUpload($request->file('bg'), $this->section->value, time() . '_' . getFileName($request->file('bg')));
            }

            if ($request->hasFile('image')) {
                if ($section && $section->image && file_exists(public_path($section->image))) {
                    Helper::fileDelete(public_path($section->image));
                }
                $validatedData['image'] = Helper::fileUpload($request->file('image'), $this->section->value, time() . '_' . getFileName($request->file('image')));
            }

            if ($request->has('rating')) {
                $validatedData['metadata']['rating'] = $validatedData['rating'];
                unset($validatedData['rating']);
            }

            if ($section) {
                CMS::where('page', $validatedData['page'])->where('section', $validatedData['section'])->update($validatedData);
            } else {
                
                // Generate a unique slug
                do {
                    $validatedData['slug'] = 'slug_'.Str::random(8);
                } while (CMS::where('slug', $validatedData['slug'])->exists());

                CMS::create($validatedData);
            }

            return redirect()->route("admin.cms.{$this->page->value}.{$this->section->value}.index")->with('t-success', 'Updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    public function display()
    {
        try {
            $pages = CMS::where('page', $this->page)->get();
            foreach ($pages as $page) {
                $page->update(['is_display' => !$page->is_display]);
            }
            return back()->with('t-success', 'Display status updated successfully.');

        } catch (Exception $e) {

            return back()->with('t-error', $e->getMessage());
            
        }
    }
    
}
