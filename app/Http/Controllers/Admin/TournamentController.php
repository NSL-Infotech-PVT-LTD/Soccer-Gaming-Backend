<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $tournament = Tournament::where('name', 'LIKE', "%$keyword%")
                ->orWhere('type', 'LIKE', "%$keyword%")
                ->orWhere('number_of_players', 'LIKE', "%$keyword%")
                ->orWhere('number_of_teams_per_player', 'LIKE', "%$keyword%")
                ->orWhere('number_of_plays_against_each_team', 'LIKE', "%$keyword%")
                ->orWhere('number_of_players_that_will_be_in_the_knockout_stage', 'LIKE', "%$keyword%")
                ->orWhere('legs_per_match_in_knockout_stage', 'LIKE', "%$keyword%")
                ->orWhere('number_of_legs_in_final', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $tournament = Tournament::latest()->paginate($perPage);
        }

        return view('admin.tournament.index', compact('tournament'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.tournament.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'name' => 'required',
			'type' => 'required',
			'number_of_players' => 'required',
			'number_of_teams_per_player' => 'required'
		]);
        $requestData = $request->all();
        
        Tournament::create($requestData);

        return redirect('admin/tournament')->with('flash_message', 'Tournament added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $tournament = Tournament::findOrFail($id);

        return view('admin.tournament.show', compact('tournament'));
    }
    
    public function showTournamentFixture($tournament_id)
    {
        dd($tournament_id);
        $tournamentfixtures = \App\TournamentFixture::findOrFail($id);

        return view('admin.tournament.showfixtures', compact('tournamentfixtures'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $tournament = Tournament::findOrFail($id);

        return view('admin.tournament.edit', compact('tournament'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
			'name' => 'required',
			'type' => 'required',
			'number_of_players' => 'required',
			'number_of_teams_per_player' => 'required'
		]);
        $requestData = $request->all();
        
        $tournament = Tournament::findOrFail($id);
        $tournament->update($requestData);

        return redirect('admin/tournament')->with('flash_message', 'Tournament updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Tournament::destroy($id);

        return redirect('admin/tournament')->with('flash_message', 'Tournament deleted!');
    }
}
