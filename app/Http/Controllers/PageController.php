<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function getPage(Request $request, $page)
    {
        try {
            $page = str_replace('-', '', $page);
            return $this->$page($request, $page);
        } catch (\Throwable $th) {
            return redirect('/');
        }
    }


    public function contributors(Request $request)
    {
        $data = new \stdClass;
        $data->contributors = Contributor::all();
        $data->title = 'TeaCode | Contributors';
//        $db_data = [];
//        try {
//            \DB::beginTransaction();
//            Contributor::truncate();
//            $data->contributors = collect($data->contributors)
//                                        ->map(function ($contributor, $key) use (&$db_data) {
//                                            // $contributor->color =  getColorRole($contributor->role);
//                                            $contributor->image = getContributorImage($contributor, $key);
//                                            $contributor->badge = getContributorBadge($contributor->role);
//                                            $db_data[] = (array)$contributor;
//                                            return $contributor;
//                                        }); //->shuffle()->values();
//
//            Contributor::insert($db_data);
//            \DB::commit();
//        } catch (\Throwable $th) {
//            \DB::rollback();
//            dd($db_data);
//            throw $th;
//        }
        return view('pages.contributors', ['data' => $data]);
    }

    public function resources(Request $request, $page)
    {
        $data = new \stdClass;
        $data->resources = json_decode(\File::get(base_path() . '/database/data/resources.json'));
        $data->title = 'TeaCode | Resources';
        return view('pages.resources', ['data' => $data]);
    }

    public function privacy(Request $request)
    {
        $data = new \stdClass;
        $data->title = 'TeaCode | Privacy Policy';
        return view('pages.privacy', ['data' => $data]);
    }

    public function terms(Request $request)
    {
        $data = new \stdClass;
        $data->title = 'TeaCode | Terms of Use';
        return view('pages.terms-of-use', ['data' => $data]);
    }

    public function getAssets(Request $request, $type = null)
    {
        try {
            $base_path = base_path();
            $type ??= 'events';
            try {
                $path = base_path('../assets/shared/img/' . $type);
                $_base_path = str_replace('base', '', $base_path);
                $files = \File::files($path);
            } catch (\Throwable $th) {
                $path = public_path('assets/shared/img/' . $type);
                $_base_path = $base_path . '\public\\';
                $files = \File::files($path);
            }
            foreach ($files as $file) {
                $file->webPath = str_replace($_base_path, '', $file->getRealPath());
            }
            $menu = json_decode(\File::get($base_path . '/database/data/admin/menu.json'));
            $data = new \stdClass;
            $data->title = 'TeaCode | Assets';
            return view('pages.admin.assets', ['data' => $data, 'files' => $files, 'menu' => $menu]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // public function rules(Request $request)
    // {
    //     $data = new \stdClass;
    //     $data->title = 'TeaCode | Rules';
    //     return view('pages.rules');
    // }

    // public function faq(Request $request)
    // {
    //     $data = new \stdClass;
    //     $data->title = 'TeaCode | FAQ';
    //     return view('pages.faq.index');
    // }
}
