<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Project_offer;
use App\Models\Project_sales_order;
use App\Models\Project_survey;
use App\Models\Work_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $project = Project::where('proj_number', 'like', '%' . $request->search . '%');
        if ($request->work_type && $request->work_type != 'All') {
            $project = $project->where('proj_work_type', $request->work_type);
        }
        if ($request->status && $request->status != 'All') {
            $project = $project->where('proj_status', $request->status);
        }
        $show = 10;
        if ($request->show && $request->show != '5') {
            $show = $request->show;
        }
        $project = $project->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        $work_type = Work_type::all();
        return view('project.index', compact('project', 'work_type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $work_type = Work_type::all();
        return view('project.create', compact('work_type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = [
            'proj_name' => 'required',
            'proj_customer' => 'required',
            'work_type_id' => 'required'
        ];
        if ($request->has('proj_number') && $request->proj_number != null) {
            $validate = [
                'proj_name' => 'required',
                'proj_customer' => 'required',
                'work_type_id' => 'required',
                'proj_number' => 'unique:projects,proj_number'
            ];
        }
        $request->validate($validate);
        DB::beginTransaction();
        try {
            $proj_number = HelperController::generate_project_number("Project", $request->proj_name, $request->proj_number != null ? $request->proj_number : '', $request->proj_number != null ? 'manul' : 'auto');
            $data = array_merge($request->except('_token', '_method'), [
                'proj_number' => $proj_number,
                'proj_status' => 'Draft'
            ]);
            $project = Project::create($data);
            if ($request->proj_status == 'Request Survey') {
                $this->create_task('Request Survey', $project->id);
            }
            DB::commit();
            return redirect()->route('project.index')->with([
                'status' => 'success',
                'message' => 'Data has been saved!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $work_type = Work_type::all();
        return view('project.show', compact('project', 'work_type'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $work_type = Work_type::all();
        return view('project.edit', compact('project', 'work_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validate = [
            'proj_name' => 'required',
            'proj_customer' => 'required',
            'work_type_id' => 'required'
        ];
        $request->validate($validate);
        DB::beginTransaction();
        try {
            $data = array_merge($request->except('_token', '_method'));
            $project->update($data);
            if ($request->proj_status == 'Request Survey') {
                $this->create_task('Request Survey', $project->id);
            }
            DB::commit();
            return redirect()->route('project.index')->with([
                'status' => 'success',
                'message' => 'Data has been saved!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        DB::beginTransaction();
        try {
            $project->delete();
            DB::commit();
            return redirect()->route('project.index')->with([
                'status' => 'success',
                'message' => 'Data has been deleted!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    /**
     * Buat update status project
     */
    public function update_status(Request $request, Project $project)
    {
        DB::beginTransaction();
        try {
            $project->proj_status = $request->proj_status;
            if ($request->proj_status == 'Request Pre Sales') {
                $project->proj_status = 'Pra-tender';
                $this->create_task($request->proj_status, $project->id);
            }
            // if ($request->proj_status == 'Request Quotation') {
            //     $project->proj_status = 'Quotation';
            //     $this->create_task($request->proj_status, $project->id);
            // }
            if ($request->proj_status == 'Request Sales Order') {
                $project->proj_status = 'Sales Order';
                $this->create_task($request->proj_status, $project->id);
            }
            $project->save();
            DB::commit();
            return redirect()->route('project.index')->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    /**
     * Buat create task untuk pre sales & sales admin
     */
    public function create_task(String $task, String $project_id)
    {
        $project = Project::find($project_id);
        if ($task == 'Request Pre Sales') {
            Project_survey::create([
                'project_id' => $project_id,
                'projsur_status' => 'Open',
            ]);

            Project_offer::create([
                'project_id' => $project_id,
                'projoff_status' => 'Open',
            ]);
            $project->proj_status = "Pra-tender";
        }
        $project->save();
    }

    /**
     * Buat membatalkan project
     */
    public function cancel(Request $request, Project $project)
    {
        DB::beginTransaction();
        try {
            $project->update([
                'proj_status' => 'Cancelled',
                'proj_cancel_message' =>  $request->message
            ]);
            Project_survey::where('project_id', $project->id)->update([
                'projsur_status' => 'Cancelled',
                'projsur_cancel_message' => "Project cancelled"
            ]);
            Project_offer::where('project_id', $project->id)->update([
                'projoff_status' => 'Cancelled',
                'projoff_cancel_message' => "Project cancelled"
            ]);
            Project_sales_order::where('project_id', $project->id)->update([
                'projso_status' => 'Cancelled',
                'projso_cancel_message' => "Project cancelled"
            ]);
            DB::commit();
            return redirect()->route('project.index')->with([
                'status' => 'success',
                'message' => 'Data has been cancelled! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    /**
     * Mengambil data work type
     */
    public function get_work_type(Request $request)
    {
        if ($request->ajax()) {
            $term = trim($request->term);
            $work_type = Work_type::selectRaw("id, name as text")
                ->where('name', 'like', '%' . $term . '%')
                ->orderBy('name')->simplePaginate(10);
            $total_count = count($work_type);
            $morePages = true;
            $pagination_obj = json_encode($work_type);
            if (empty($work_type->nextPageUrl())) {
                $morePages = false;
            }
            $result = [
                "results" => $work_type->items(),
                "pagination" => [
                    "more" => $morePages
                ],
                "total_count" => $total_count
            ];
            return response()->json($result);
        }
    }
}
