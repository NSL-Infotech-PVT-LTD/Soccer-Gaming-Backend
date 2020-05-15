<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Team;
use Illuminate\Http\Request;
use DataTables;

class TeamsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
//    public function index(Request $request) {
//        $keyword = $request->get('search');
//        $perPage = 25;
//
//        if (!empty($keyword)) {
//            $teams = Team::where('team_name', 'LIKE', "%$keyword%")
//                            ->orWhere('league_name', 'LIKE', "%$keyword%")
//                            ->orWhere('image', 'LIKE', "%$keyword%")
//                            ->latest()->paginate($perPage);
//        } else {
//            $teams = Team::latest()->paginate($perPage);
//        }
//
//        return view('admin.teams.index', compact('teams'));
//    }

    protected $__rulesforindex = ['team_name' => 'required', 'image' => 'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $team = Team::all();
            return Datatables::of($team)
                            ->addIndexColumn()
                            ->editColumn('image', function($item) {
                                return "<img width='50' src=" . url($item->image) . ">";
                            })
                            ->addColumn('action', function($item) {
                                $return = '';
                                $return .= " <a href=" . url('/admin/teams/' . $item->id) . " title='View Driver'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>";
                                return $return;
                            })
                            ->rawColumns(['action','image'])
                            ->make(true);
        }
        return view('admin.teams.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.teams.create');
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
        $image = $this->uploadFile($request, 'image', '/uploads/team_image');
        $requestData['image'] = $image;
        Team::create($requestData);

        return redirect('admin/teams')->with('flash_message', 'Team added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $team = Team::findOrFail($id);

        return view('admin.teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $team = Team::findOrFail($id);

        return view('admin.teams.edit', compact('team'));
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

        $team = Team::findOrFail($id);
        if (isset($request->image)) {
            $image = $this->uploadFile($request, 'image', '/uploads/team_image');


            $requestData['image'] = $image;
        } else {
            $requestData['image'] = $team->image;
        }
        $team->update($requestData);

        return redirect('admin/teams')->with('flash_message', 'Team updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        Team::destroy($id);

        return redirect('admin/teams')->with('flash_message', 'Team deleted!');
    }

}
