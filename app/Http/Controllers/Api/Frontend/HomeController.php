<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Enums\PageEnum;
use App\Enums\SectionEnum;
use App\Helpers\Helper;
use App\Models\CMS;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $data = [];

        $cmsItems = CMS::query()
                    ->where('page', PageEnum::HOME)
                    ->where('status', 'active')
                    ->whereIn('section', [SectionEnum::HOME_EXAMPLE, SectionEnum::HOME_EXAMPLES])
                    ->get();

        $data['home_example']    = $cmsItems->where('section', SectionEnum::HOME_EXAMPLE)->first();
        $data['home_examples']   = $cmsItems->where('section', SectionEnum::HOME_EXAMPLES)->values();
        $data['common']         = CMS::where('page', PageEnum::COMMON)->where('status', 'active')->get();
        $data['settings']       = Setting::first();

        return Helper::jsonResponse(true, 'Home Page', 200, $data);

    }
}
