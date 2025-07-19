<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Customer;
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
        $customer = Customer::all();
        $brand = Brand::all();
        return view('project.create', compact(
            'work_type',
            'customer',
            'brand'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = [
            'proj_name' => 'required',
            'customer_id' => 'required',
        ];
        if ($request->has('proj_number') && $request->proj_number != null) {
            $validate = [
                'proj_name' => 'required',
                'customer_id' => 'required',
                'proj_number' => 'unique:projects,proj_number'
            ];
        }
        $request->validate($validate);
        DB::beginTransaction();
        try {
            $proj_number = HelperController::generate_project_number(
                "Project",
                $request->proj_name,
                $request->proj_number != null ? $request->proj_number : '',
                $request->proj_number != null ? 'manul' : 'auto'
            );
            $data = array_merge($request->except('_token', '_method'), [
                'proj_number' => $proj_number,
                'proj_status' => 'Draft',
                'proj_denah' => $request->proj_denah ? 1 : null,
                'proj_shop' => $request->proj_shop ? 1 : null,
                'proj_sld' => $request->proj_sld ? 1 : null,
                'proj_rab' => $request->proj_rab ? 1 : null,
                'proj_personil' => $request->proj_personil ? 1 : null,
                'proj_schedule' => $request->proj_schedule ? 1 : null,
            ]);
            $project = Project::create($data);
            if ($request->proj_status == 'Pre Sales') {
                $this->create_task('Pre Sales', $project->id);
            }
            if ($request->has('brand_id')) {
                $project->project_brand()->attach($request->brand_id);
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
        $customer = Customer::all();
        $brand = Brand::all();
        return view('project.show', compact(
            'project',
            'work_type',
            'customer',
            'brand'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $work_type = Work_type::all();
        $customer = Customer::all();
        $brand = Brand::all();
        return view('project.edit', compact(
            'project',
            'work_type',
            'customer',
            'brand'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validate = [
            'proj_name' => 'required',
            'customer_id' => 'required',
        ];
        $request->validate($validate);
        DB::beginTransaction();
        try {
            $data = array_merge($request->except('_token', '_method'), [
                'proj_denah' => $request->proj_denah ? 1 : null,
                'proj_shop' => $request->proj_shop ? 1 : null,
                'proj_sld' => $request->proj_sld ? 1 : null,
                'proj_rab' => $request->proj_rab ? 1 : null,
                'proj_personil' => $request->proj_personil ? 1 : null,
                'proj_schedule' => $request->proj_schedule ? 1 : null
            ]);
            $project->update($data);
            if ($request->proj_status == 'Pra-tender') {
                $this->create_task('Pra-tender', $project->id);
            }
            if ($request->has('brand_id')) {
                $project->project_brand()->sync($request->brand_id);
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
            if (!in_array($request->proj_status, ['Assign Pre Sales', 'Assign Sales Admin'])) {
                $project->proj_status = $request->proj_status;
            }
            $this->create_task($request->proj_status, $project->id);
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

        $mapping = [
            'Pre Sales' => function () use ($project_id, $project) {
                Project_survey::create([
                    'project_id' => $project_id,
                    'projsur_number' => HelperController::generate_code("Pre Sales"),
                    'projsur_status' => 'Open',
                ]);
                Project_offer::create([
                    'project_id' => $project_id,
                    'projoff_number' => HelperController::generate_code("Sales Admin - Quotation"),
                    'projoff_status' => 'Open',
                ]);
                $project->proj_status = "Pre Sales";
            },
            'Assign Pre Sales' => function () use ($project_id) {
                Project_survey::create([
                    'project_id' => $project_id,
                    'projsur_number' => HelperController::generate_code("Pre Sales"),
                    'projsur_status' => 'Open',
                ]);
            },
            'Assign Sales Admin' => function () use ($project_id) {
                Project_offer::create([
                    'project_id' => $project_id,
                    'projoff_number' => HelperController::generate_code("Sales Admin - Quotation"),
                    'projoff_status' => 'Open',
                ]);
            },
            'Sales Order' => function () use ($project_id, $project) {
                Project_sales_order::create([
                    'project_id' => $project_id,
                    'projso_number' => HelperController::generate_code("Sales Admin - Sales Order"),
                    'projso_status' => 'Open',
                ]);
                $project->proj_status = "Sales Order";
            },
        ];

        if (isset($mapping[$task])) {
            $mapping[$task]();
            $project->save();
        }
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
            $work_type = Work_type::selectRaw("id, work_name as text")
                ->where('work_name', 'like', '%' . $term . '%')
                ->orderBy('work_name')->simplePaginate(10);
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

    public function get_customer(Request $request)
    {
        if ($request->ajax()) {
            $term = trim($request->term);
            $customer = Customer::selectRaw("id, cust_name as text")
                ->where('cust_name', 'like', '%' . $term . '%')
                ->orderBy('cust_name')->simplePaginate(10);
            $total_count = count($customer);
            $morePages = true;
            $pagination_obj = json_encode($customer);
            if (empty($customer->nextPageUrl())) {
                $morePages = false;
            }
            $result = [
                "results" => $customer->items(),
                "pagination" => [
                    "more" => $morePages
                ],
                "total_count" => $total_count
            ];
            return response()->json($result);
        }
    }
}
