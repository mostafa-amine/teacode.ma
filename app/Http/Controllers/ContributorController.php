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
            return DataTables::eloquent(Contributor::orderBy('id'))->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function updateContributors(Request $request)
    {
        $contributor_id = $request->get('contributor_id');
        $contributor = null;
        if ($contributor_id) {
            $contributor = Contributor::find($contributor_id);
            $path = $contributor->image;
        }
        if ($request->has('deleting')) {
            $contributor->delete();
            return ['msg' => 'Deleted Successfully', 'contributor' => $contributor];
        }
        $image = $request->file('image');
        if ($image) {
            $image_ext = $image->clientExtension();
            $image_name = $request->get('slug') . "." . $image_ext;
            // $x = $request->file('image')->storeAs('/public/images/people/contributors', $image_name);
            $path = \Storage::disk('public')->putFileAs('images/people/contributors', $image, $image_name);
            $path = "/storage/$path";
        }
        $data = [
            'fullname' => $request->get('fullname'),
            'role' => $request->get('role'),
            'slug' => $request->get('slug'),
            'image' => $path,
        ];
        if ($contributor) {
            $contributor->update($data);
        } else {
            Contributor::create($data);
        }
        return redirect('/admin/contributors');
    }
}
