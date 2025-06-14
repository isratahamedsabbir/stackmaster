<?php

namespace App\Http\Controllers\Web\Backend\CMS\Web\Home;

use App\Http\Controllers\Controller;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;
use App\Http\Requests\CmsRequest;
use App\Services\CmsService;

class HomeAboutController extends Controller
{
    protected $cmsService;

    public $name = "home";
    public $section = "about";
    public $page = PageEnum::HOME;
    public $item = SectionEnum::HOME_ABOUT;

    public function __construct(CmsService $cmsService)
    {
        $this->cmsService = $cmsService;
    }

    public function index()
    {
        $data = CMS::where('page', $this->page)->where('section', $this->item)->latest()->first();
        return view("backend.layouts.cms.{$this->name}.{$this->section}.index", ["data" => $data, "name" => $this->name, "section" => $this->section]);
    }
    
    public function show($id)
    {
        $data = CMS::where('id', $id)->first();
        return view("backend.layouts.cms.{$this->name}.{$this->section}.show", ["data" => $data, "name" => $this->name, "section" => $this->section]);
    }

    public function content(CmsRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $validatedData['page'] = $this->page;
            $validatedData['section'] = $this->item;
            $section = CMS::where('page', $this->page)->where('section', $this->item)->first();

            if($request->hasFile('bg')) {
                if ($section && $section->bg && file_exists(public_path($section->bg))) {
                    Helper::fileDelete(public_path($section->bg));
                }
                $validatedData['bg'] = Helper::fileUpload($request->file('bg'), $this->section, time() . '_' . getFileName($request->file('bg')));
            }

            if ($request->hasFile('image')) {
                if ($section && $section->image && file_exists(public_path($section->image))) {
                    Helper::fileDelete(public_path($section->image));
                }
                $validatedData['image'] = Helper::fileUpload($request->file('image'), $this->section, time() . '_' . getFileName($request->file('image')));
            }

            if($request->has('rating')) {
                $validatedData['metadata']['rating'] = $validatedData['rating'];
                unset($validatedData['rating']);
            }

            if ($section) {
                CMS::where('page', $validatedData['page'])->where('section', $validatedData['section'])->update($validatedData);
            } else {
                CMS::create($validatedData);
            }

            return redirect()->route("admin.cms.{$this->name}.{$this->section}.index")->with('t-success', 'Updated successfully');
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
