<?php
namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Quiz;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class QuizController extends Controller
{
    public $part;
    public $route;
    public $view;

    public function __construct()
    {
        $this->part  = 'quiz';
        $this->route = 'admin.' . $this->part;
        $this->view  = 'backend.layouts.' . $this->part;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Quiz::with(['subcategory'])->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('subcategory', function ($data) {
                    return "<a href='" . route('admin.subcategory.show', $data->subcategory_id) . "'>" . $data->subcategory->name . "</a>";
                })
                ->addColumn('question', function ($data) {
                    return Str::limit($data->question, 20);
                })
                ->addColumn('status', function ($data) {

                    $backgroundColor  = $data->status == "active" ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status == "active" ? '26px' : '2px';
                    $sliderStyles     = "position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: transform 0.3s ease; transform: translateX($sliderTranslateX);";

                    $status = '<div class="form-check form-switch" style="margin-left:40px; position: relative; width: 50px; height: 24px; background-color: ' . $backgroundColor . '; border-radius: 12px; transition: background-color 0.3s ease; cursor: pointer;">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status" style="position: absolute; width: 100%; height: 100%; opacity: 0; z-index: 2; cursor: pointer;">';
                    $status .= '<span style="' . $sliderStyles . '"></span>';
                    $status .= '<label for="customSwitch' . $data->id . '" class="form-check-label" style="margin-left: 10px;"></label>';
                    $status .= '</div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {

                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">

                                <a href="#" type="button" onclick="goToEdit(' . $data->id . ')" class="btn btn-primary fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-edit"></i>
                                </a>

                                <a href="#" type="button" onclick="goToOpen(' . $data->id . ')" class="btn btn-success fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-eye"></i>
                                </a>

                                <a href="#" type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger fs-14 text-white delete-icn" title="Delete">
                                    <i class="fe fe-trash"></i>
                                </a>

                            </div>';
                })
                ->rawColumns(['subcategory', 'question', 'status', 'action'])
                ->make();
        }

        return view($this->view . ".index", [
            'part'  => $this->part,
            'route' => $this->route,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = Category::where('slug', 'quiz')->first();
        if($category){
            $subcategories = Subcategory::where('status', 'active')->where('category_id', $category->id)->get();
        }else{
            $subcategories = Subcategory::where('status', 'active')->get();
        }
        return view($this->view . ".create", [
            'subcategories' => $subcategories,
            'route'      => $this->route,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question'          => 'required|string|max:1000',
            'subcategory_id'    => 'required|exists:subcategories,id',
            'option.*'          => 'required|string|max:255',
            'answer'      => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (! in_array($value, $request->input('option'))) {
                        $fail('The ' . $attribute . ' must be in one of the options.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $validator->validated();

            $quiz = new Quiz();

            $quiz->question         = $data['question'];
            $dua->category_id       = Category::where('slug', 'dua')->first()->id ?? null;
            $quiz->subcategory_id   = $data['subcategory_id'];
            $quiz->answer           = $data['answer'];
            $quiz->options          = json_encode($data['option']);
            $quiz->save();

            session()->put('t-success', 'created successfully');
        } catch (Exception $e) {

            session()->put('t-error', $e->getMessage());
        }

        return redirect()->route($this->route . '.index')->with('t-success', 'created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz, $id)
    {
        $quiz = Quiz::with(['category', 'subcategory'])->where('id', $id)->first();
        return view($this->view . ".show", [
            'quiz'  => $quiz,
            'route' => $this->route,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz, $id)
    {
        $quiz       = Quiz::findOrFail($id);
        $category   = Category::where('slug', 'quiz')->first();

        if($category){
            $subcategories = Subcategory::where('status', 'active')->where('category_id', $category->id)->get();
        }else{
            $subcategories = Subcategory::where('status', 'active')->get();
        }
        
        return view($this->view . ".edit", [
            'quiz'       => $quiz,
            'subcategories' => $subcategories,
            'route'      => $this->route,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question'    => 'required|string|max:1000',
            'subcategory_id' => 'required|exists:subcategories,id',
            'option.*'    => 'required|string|max:255',
            'answer'      => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (! in_array($value, $request->input('option'))) {
                        $fail('The ' . $attribute . ' must be in one of the options.');
                    }
                },
            ]
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $validator->validated();

            $quiz = Quiz::findOrFail($id);

            $quiz->question    = $data['question'];
            $quiz->subcategory_id = $data['subcategory_id'];
            $quiz->answer      = $data['answer'];
            $quiz->options     = json_encode($data['option']);
            $quiz->save();

            session()->put('t-success', 'updated successfully');
        } catch (Exception $e) {

            session()->put('t-error', $e->getMessage());
        }

        return redirect()->route($this->route . '.edit', $quiz->id)->with('t-success', 'updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $data = Quiz::findOrFail($id);

            $data->delete();
            return response()->json([
                'status'  => 't-success',
                'message' => 'Your action was successful!',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 't-error',
                'message' => 'Your action was successful!',
            ]);
        }
    }

    public function status(int $id): JsonResponse
    {
        $data = Quiz::findOrFail($id);
        if (! $data) {
            return response()->json([
                'status'  => 't-error',
                'message' => 'Item not found.',
            ]);
        }
        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();
        return response()->json([
            'status'  => 't-success',
            'message' => 'Your action was successful!',
        ]);
    }
}
