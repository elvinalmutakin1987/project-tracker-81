<?php

namespace App\Http\Controllers;

use App\Models\File_upload;
use App\Models\Project;
use App\Models\Project_offer;
use App\Models\Project_survey;
use App\Models\Work_type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class TaskBoardController extends Controller
{
    public function index(Request $request)
    {
        $assignee = "pre-sales";
        if ($request->has('assignee')) {
            $assignee = $request->assignee;
        }
        $project_survey = Project_survey::whereIn('projsur_status', [
            'Open',
            'Started',
            'Hold'
        ])->get();
        $project_offer = Project_offer::whereIn('projoff_status', [
            'Open',
            'Started',
            'Hold',
            'Revisi Mesin',
            'Approval'
        ])->get();
        $view = 'task_board.pre_sales';
        if ($assignee == 'sales-admin') {
            $view = 'task_board.sales_admin';
        }
        if ($assignee == 'operation') {
            $view = 'task_board.sales_admin';
        }
        if ($assignee == 'finance-accounting') {
            $view = 'task_board.sales_admin';
        }
        return view($view, compact('project_survey', 'project_offer', 'assignee'));
    }

    public function show(Request $request, Project $project)
    {
        $work_type = Work_type::all();
        $assignee = $request->assignee;
        return view('task_board.show', compact('project', 'work_type', 'assignee'));
    }

    /**
     * Pre Sales
     */
    public function take_survey(Request $request, Project_survey $project_survey)
    {
        DB::beginTransaction();
        try {
            $project_survey->update([
                'user_id' => Auth::user()->id,
                'projsur_started_at' => Carbon::now(),
                'projsur_status' => "Started"
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'pre-sales'])->with([
                'status' => 'success',
                'message' => 'Data has been taken! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function hold_survey(Request $request, Project_survey $project_survey)
    {
        DB::beginTransaction();
        try {
            $project_survey->update([
                'projsur_status' => "Hold",
                'projsur_hold_message' => $request->message
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'pre-sales'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function finish_survey(Request $request, Project_survey $project_survey)
    {
        DB::beginTransaction();
        try {
            $project_survey->update([
                'projsur_status' => "Finished",
                'projsur_hold_message' => $request->message
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'pre-sales'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function document_survey(Request $request, Project_survey $project_survey)
    {
        return view('task_board.document_survey', compact('project_survey'));
    }

    public function document_survey_update(Request $request, Project_survey $project_survey)
    {
        DB::beginTransaction();
        try {
            if ($request->has('file_upload')) {
                $file = $request->file('file_upload');
                $file_real_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $file_directory = "presales";
                $file_name = Str::random(24) . "." . $file_ext;
                $file->storeAs($file_directory, $file_name);
                // Storage::disk('local')->put($file_name, file_get_contents($request->file('file_upload')->getRealPath()));

                File_upload::create([
                    'file_doc_type' => $request->file_doc_type,
                    'file_table' => 'project_survey',
                    'file_table_id' => $project_survey->id,
                    'file_directory' => $file_directory,
                    'file_name' => $file_name,
                    'file_real_name' => $file_real_name,
                    'file_ext' => $file_ext
                ]);
            }
            if ($request->file_doc_type == 'Denah') {
                $project_survey->projsur_denah = 1;
            }
            if ($request->file_doc_type == 'Shop Drawing') {
                $project_survey->projsur_shop = 1;
            }
            if ($request->file_doc_type == 'SLD/Topology') {
                $project_survey->projsur_sld = 1;
            }
            if ($request->file_doc_type == 'RAB/BOQ/Budget') {
                $project_survey->projsur_rab = 1;
            }
            if ($request->file_doc_type == 'Personil') {
                $project_survey->projsur_personil = 1;
            }
            if ($request->file_doc_type == 'Schedule') {
                $project_survey->projsur_schedule = 1;
            }
            $project_survey->save();
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'pre-sales'])->with([
                'status' => 'success',
                'message' => 'Data has been uploaded! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    /**
     * Sales Admin
     */
    public function take_offering(Request $request, Project_survey $project_offer) {}

    public function take_salesorder(Request $request, Project_survey $project_offer) {}

    public function take_workorder(Request $request, Project_survey $project_offer) {}

    /**
     * Operation
     */

    /**
     * Finance & Accounting
     */
}
