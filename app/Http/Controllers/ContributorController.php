<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContributorController extends Controller
{

    public function getContributors(Request $request)
    {
        try {
            $data = new \stdClass;
            $data->title = "TeaCode | Contributors List";
            $menu = json_decode(\File::get(base_path() . '/database/data/admin/menu.json'));
            if ($request->has('api')) {
                $contributors = Contributor::withTrashed()->orderBy('deleted_at')->orderBy('id');
                return DataTables::eloquent($contributors)->make(true);
            }
            return view('pages.admin.contributors', ['menu' => $menu, 'data' => $data]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function updateContributors(Request $request)
    {
        $contributor_id = $request->get('contributor_id');
        $contributor = null;
        if ($contributor_id) {
            $contributor = Contributor::withTrashed()->find($contributor_id);
            $path = $contributor->image;
        }
        if ($request->has('delete')) {
            $contributor->delete();
            return ['message' => 'Deleted Successfully', 'contributor' => $contributor];
        }
        if ($request->has('restore')) {
            $contributor->restore();
            return ['message' => 'Restored Successfully', 'contributor' => $contributor];
        }
        $image = $request->file('image');
        if ($image) {
            $image_ext = $image->clientExtension();
            $image_name = $request->get('slug') . "." . $image_ext;
            // $x = $request->file('image')->storeAs('/public/images/people/contributors', $image_name);
            $path = \Storage::disk('public')->putFileAs('images/people/contributors', $image, $image_name);
            $path = "/storage/$path";
        } else if (!$contributor_id) {
            $defaultImages = \Storage::disk('public')->files('images/logos');
            $randomImage = $defaultImages[rand(0, count($defaultImages) - 1)];
            $path = "/storage/$randomImage";
        }
        $data = [
            'fullname' => $request->get('fullname'),
            'role' => $request->get('role'),
            'slug' => $request->get('slug'),
            'image' => $path,
        ];
        if ($contributor) {
            $contributor->update($data);
            $data = ['message' => 'Contributor Updated', 'contributor' => $contributor];
        } else {
            $contributor = Contributor::create($data);
            $data = ['message' => 'Contributor Created', 'contributor' => $contributor];
        }
        return $data;
    }
}
