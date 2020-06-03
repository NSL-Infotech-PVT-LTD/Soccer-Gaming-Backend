<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Banner;
use Illuminate\Http\Request;
use DataTables;

class BannerController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
//    public function index(Request $request)
//    {
//        $keyword = $request->get('search');
//        $perPage = 25;
//
//        if (!empty($keyword)) {
//            $banner = Banner::where('name', 'LIKE', "%$keyword%")
//                ->orWhere('image', 'LIKE', "%$keyword%")
//                ->latest()->paginate($perPage);
//        } else {
//            $banner = Banner::latest()->paginate($perPage);
//        }
//
//        return view('admin.banner.index', compact('banner'));
//    }
    protected $__rulesforindex = ['name' => 'required', 'image' => 'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $banner = Banner::all();
            return Datatables::of($banner)
                            ->addIndexColumn()
                            ->editColumn('image', function($item) {
                                return "<img width='50' src=" . url('uploads/banner_image/' . $item->image) . ">";
                            })
                            ->addColumn('action', function($item) {
                                $return = '';
                                $return .= " <a href=" . url('/admin/banner/' . $item->id) . " title='View Banner'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>";
                                $return .= " <a href=" . url('/admin/banner/' . $item->id . '/edit') . " title='Edit Banner'><button class='btn btn-primary btn-sm'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></button></a>";

                                $return .= " <button class='btn btn-danger btn-sm btnDelete' type='submit' data-remove='" . url('/admin/banner/' . $item->id) . "'><i class='fa fa-trash-o' aria-hidden='true'></i></button>";

                                return $return;
                            })
                            ->rawColumns(['action', 'image'])
                            ->make(true);
        }
        return view('admin.banner.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {

        $requestData = $request->all();
        $image = $this->uploadFile($request, 'image', '/uploads/banner_image');
        $requestData['image'] = $image;
        Banner::create($requestData);

        return redirect('admin/banner')->with('flash_message', 'Banner added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $banner = Banner::findOrFail($id);

        return view('admin.banner.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $banner = Banner::findOrFail($id);

        return view('admin.banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id) {

        $requestData = $request->all();

        $banner = Banner::findOrFail($id);
        if (isset($request->image)) {
            $image = $this->uploadFile($request, 'image', '/uploads/banner_image');


            $requestData['image'] = $image;
        } else {
            $requestData['image'] = $banner->image;
        }
        $banner->update($requestData);

        return redirect('admin/banner')->with('flash_message', 'Banner updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
//    public function destroy($id) {
//        Banner::destroy($id);
//
//        return redirect('admin/banner')->with('flash_message', 'Banner deleted!');
//    }

    public function destroy($id) {
        if (Banner::destroy($id)) {
            $data = 'Success';
        } else {
            $data = 'Failed';
        }
        return response()->json($data);
    }

}
