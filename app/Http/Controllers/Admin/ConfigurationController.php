<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $configuration = Configuration::where('about_us_customer', 'LIKE', "%$keyword%")
                            ->orWhere('about_us_service_provider', 'LIKE', "%$keyword%")
                            ->orWhere('terms_and_conditions_customer', 'LIKE', "%$keyword%")
                            ->orWhere('terms_and_conditions_service_provider', 'LIKE', "%$keyword%")
                            ->orWhere('private_policy_customer', 'LIKE', "%$keyword%")
                            ->orWhere('private_policy_service_provider', 'LIKE', "%$keyword%")
                            ->latest()->paginate($perPage);
        } else {
            $configuration = Configuration::latest()->paginate($perPage);
        }

        return view('admin.configuration.index', compact('configuration'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.configuration.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {
        $this->validate($request, [
            'about_us_customer' => 'required',
            'about_us_service_provider' => '',
            'terms_and_conditions_customer' => 'required',
            'terms_and_conditions_service_provider' => '',
            'private_policy_customer' => 'required',
            'private_policy_service_provider' => ''
        ]);
        $requestData = $request->all();

        Configuration::create($requestData);

        return redirect('admin/configuration')->with('flash_message', 'Configuration added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $configuration = Configuration::findOrFail($id);

        return view('admin.configuration.show', compact('configuration'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $configuration = Configuration::findOrFail($id);

        return view('admin.configuration.edit', compact('configuration'));
    }

    public function customEdit() {
        $id = '1';
        $configuration = Configuration::findOrFail($id);

        return view('admin.configuration.edit', compact('configuration'));
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
        $this->validate($request, [
            'about_us_customer' => 'required',
            'about_us_service_provider' => '',
            'terms_and_conditions_customer' => 'required',
            'terms_and_conditions_service_provider' => '',
            'private_policy_customer' => 'required',
            'private_policy_service_provider' => ''
        ]);
        $requestData = $request->all();

        $configuration = Configuration::findOrFail($id);
        $configuration->update($requestData);

        return redirect()->back()->with('flash_message', 'Configuration updated!');
//              return redirect('admin/configuration')->with('flash_message', 'Configuration updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        Configuration::destroy($id);

        return redirect('admin/configuration')->with('flash_message', 'Configuration deleted!');
    }

}
