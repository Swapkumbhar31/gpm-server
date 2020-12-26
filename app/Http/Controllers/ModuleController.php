<?php

namespace App\Http\Controllers;

use App\Http\Resources\Module as ModuleResource;
use App\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::orderBy('mod_index')->paginate(5);
        return view('admin.module.index')->with(['modules' => $modules,
            'page_title' => 'Arena'
        ]);
        return ModuleResource::collection($modules);
    }

    public function show($id)
    {
        $module = Module::findOrFail($id);
        return new ModuleResource($module);
    }

    public function store(Request $request)
    {
        $module = $request->isMethod('put') ? Module::findOrFail($request->module_id) : new Module;
        $module->id = $request->input('module_id');
        $module->name = $request->input('name');
        $module->description = $request->input('description');
        $module->mod_index = $request->input('mod_index');
        if ($module->save()) {
            if ($request->wantsJson()) {
                return new ModuleResource($module);
            } else {
                return redirect(route('modules'));
            }
        }
    }

    public function create()
    {
        return view('admin.module.add');
    }

    public function destroy(Request $request, $id)
    {
        $module = Module::findOrFail($id);
        if ($module->delete()) {
            if ($request->wantsJson()) {
                return new ModuleResource($module);
            } else {
                return redirect()->back();
            }
        }
    }

    public function Syllabus()
    {
        $modules = Module::orderBy('mod_index')->get();
        return ModuleResource::collection($modules);
    }
    public function edit($module_id)
    {
        $module = Module::findOrFail($module_id);
        return view('admin.module.edit')->with([
            'module' => $module,
        ]);
    }
}
