<?php

namespace App\Http\Controllers;

use App\Models\File_upload;
use App\Models\Project;
use App\Models\Project_invoice;
use App\Models\Project_invoice_dp;
use App\Models\Project_offer;
use App\Models\Project_sales_order;
use App\Models\Project_survey;
use App\Models\Project_work_order;
use App\Models\Work_type;
use App\Models\Work_order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TaskBoardController extends Controller
{
    public function index(Request $request)
    {
        $assignee = "pre-sales";
        if ($request->has('assignee')) {
            $assignee = $request->assignee;
        } else {
            $permissions = [
                'task_board.pre_sales' => 'pre-sales',
                'task_board.sales_admin' => 'sales-admin',
                'task_board.finance_accounting' => 'finance-accounting',
                'task_board.operation' => 'operation',
            ];
            foreach ($permissions as $permission => $role) {
                if (Auth::user()->hasPermissionTo($permission)) {
                    $assignee = $role;
                    break; // keluar dari loop setelah satu ketemu
                }
            }
        }
        $show = 10;
        if ($request->show && $request->show != '5') {
            $show = $request->show;
        }
        /**
         * ================================================================================================
         */

        /**
         * Survey
         */
        $project_survey = Project_survey::select('project_surveys.*', 'projects.proj_number')
            ->leftJoin('projects', 'projects.id', '=', 'project_surveys.project_id')
            ->where('proj_number', 'like', '%' . $request->search . '%');
        if ($request->status && $request->status != 'All') {
            $project_survey = $project_survey->where('projsur_status', $request->status);
        }
        if ($request->taker && $request->taker != 'All') {
            $project_survey = $project_survey->where('user_id', Auth::user()->id);
        }
        $project_survey = $project_survey->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        /**
         * Offer
         */
        $project_offer = Project_offer::select('project_offers.*', 'projects.proj_number')
            ->leftJoin('projects', 'projects.id', '=', 'project_offers.project_id')
            ->where('proj_number', 'like', '%' . $request->search . '%');
        if ($request->status && $request->status != 'All') {
            $project_offer = $project_offer->where('projoff_status', $request->status);
        }
        if ($request->taker && $request->taker != 'All') {
            $project_offer = $project_offer->where('user_id', Auth::user()->id);
        }
        $project_offer = $project_offer->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        /**
         * Sales Order
         */
        $project_sales_order = Project_sales_order::select('project_sales_orders.*', 'projects.proj_number')
            ->leftJoin('projects', 'projects.id', '=', 'project_sales_orders.project_id')
            ->where('proj_number', 'like', '%' . $request->search . '%');
        if ($request->status && $request->status != 'All') {
            $project_sales_order = $project_sales_order->where('projso_status', $request->status);
        }
        if ($request->taker && $request->taker != 'All') {
            $project_sales_order = $project_sales_order->where('user_id', Auth::user()->id);
        }
        $project_sales_order = $project_sales_order->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        /**
         * Permit WO
         */
        $permit_wo = Project_invoice_dp::select('project_invoice_dps.*', 'projects.proj_number')
            ->leftJoin('projects', 'projects.id', '=', 'project_invoice_dps.project_id')
            ->where('projinvdp_permit_wo', '1')
            ->whereNull('projinvdp_create_wo');
        if ($request->status && $request->status != 'All') {
            $permit_wo = $permit_wo->where('projinvdp_status', $request->status);
        }
        if ($request->taker && $request->taker != 'All') {
            $permit_wo = $permit_wo->where('user_id', Auth::user()->id);
        }
        $permit_wo = $permit_wo->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));

        /**
         * Invoice DP
         */
        $project_invoice_dp = Project_invoice_dp::select('project_invoice_dps.*', 'projects.proj_number')
            ->leftJoin('projects', 'projects.id', '=', 'project_invoice_dps.project_id')
            ->where('proj_number', 'like', '%' . $request->search . '%');
        if ($request->status && $request->status != 'All') {
            $project_invoice_dp = $project_invoice_dp->where('projinvdp_status', $request->status);
        }
        if ($request->taker && $request->taker != 'All') {
            $project_invoice_dp = $project_invoice_dp->where('user_id', Auth::user()->id);
        }
        $project_invoice_dp = $project_invoice_dp->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        /**
         * Work Order
         */
        $work_order = Work_order::select('work_orders.*', 'projects.proj_number')
            ->leftJoin('projects', 'projects.id', '=', 'work_orders.project_id')
            ->where('proj_number', 'like', '%' . $request->search . '%');
        if ($request->status && $request->status != 'All') {
            $work_order = $work_order->where('projwo_status', $request->status);
        }
        if ($request->taker && $request->taker != 'All') {
            $work_order = $work_order->where('user_id', Auth::user()->id);
        }
        $work_order = $work_order->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        /**
         * ================================================================================================
         */
        if ($assignee == 'pre-sales') {
            $doc_type = "pre-sales";
            $view = 'task_board.pre_sales';
            $table = view('task_board.table_survey', compact(
                'project_survey',
                'assignee',
                'doc_type'
            ));
            /**
             * cek hak akses
             */
            if (!Auth::user()->hasPermissionTo('task_board.pre_sales')) {
                return view('error', [
                    'message' => "You’re not authorized to view this page."
                ]);
            }
        }

        if ($assignee == 'sales-admin') {
            $doc_type = "quotation";
            if ($request->has('doc_type')) {
                $doc_type = $request->doc_type;
            }
            $view = 'task_board.sales_admin';
            if ($doc_type == 'work-order') {
                $view = 'task_board.sales_admin_permit_wo';
            }
            $docMap = [
                'quotation'    => [
                    'view' => 'task_board.table_offer',
                    'data' => 'project_offer'
                ],
                'sales-order'  => [
                    'view' => 'task_board.table_so',
                    'data' => 'project_sales_order'
                ],
                'work-order'  => [
                    'view' => 'task_board.table_permit_wo',
                    'data' => 'permit_wo'
                ]
            ];
            if (isset($docMap[$doc_type])) {
                $viewName  = $docMap[$doc_type]['view'];
                $dataKey   = $docMap[$doc_type]['data'];
                $dataValue = $$dataKey;
                $table = view($viewName, [
                    $dataKey => $dataValue,
                    'assignee' => $assignee,
                    'doc_type' => $doc_type
                ]);
            }
            /**
             * cek hak akses
             */
            if (!Auth::user()->hasPermissionTo('task_board.sales_admin')) {
                return view('error', [
                    'message' => "You’re not authorized to view this page."
                ]);
            }
        }

        if ($assignee == 'finance-accounting') {
            $doc_type = "invoice-dp";
            if ($request->has('doc_type')) {
                $doc_type = $request->doc_type;
            }
            $view = 'task_board.fa_invoice_dp';
            $docMap = [
                'invoice-dp'    => [
                    'view' => 'task_board.table_invoice_dp',
                    'data' => 'project_invoice_dp'
                ],
                'invoice'  => [
                    'view' => 'task_board.table_invoice',
                    'data' => 'project_invoice'
                ],
            ];
            if (isset($docMap[$doc_type])) {
                $viewName  = $docMap[$doc_type]['view'];
                $dataKey   = $docMap[$doc_type]['data'];
                $dataValue = $$dataKey;
                $table = view($viewName, [
                    $dataKey => $dataValue,
                    'assignee' => $assignee,
                    'doc_type' => $doc_type
                ]);
            }
            /**
             * cek hak akses
             */
            if (!Auth::user()->hasPermissionTo('task_board.finance_accounting')) {
                return view('error', [
                    'message' => "You’re not authorized to view this page."
                ]);
            }
        }

        if ($assignee == 'operation') {
            $doc_type = "work-order";
            if ($request->has('doc_type')) {
                $doc_type = $request->doc_type;
            }
            $view = 'task_board.operation_work_order';
            $docMap = [
                'work-order'    => [
                    'view' => 'task_board.table_wo',
                    'data' => 'work_order'
                ],
                'invoice'  => [
                    'view' => 'task_board.table_invoice',
                    'data' => 'project_invoice'
                ],
            ];
            if (isset($docMap[$doc_type])) {
                $viewName  = $docMap[$doc_type]['view'];
                $dataKey   = $docMap[$doc_type]['data'];
                $dataValue = $$dataKey;
                $table = view($viewName, [
                    $dataKey => $dataValue,
                    'assignee' => $assignee,
                    'doc_type' => $doc_type
                ]);
            }
            /**
             * cek hak akses
             */
            if (!Auth::user()->hasPermissionTo('task_board.operation')) {
                return view('error', [
                    'message' => "You’re not authorized to view this page."
                ]);
            }
        }
        /**
         * ================================================================================================
         */
        $tab = view('task_board.tab', compact(
            'project_survey',
            'project_offer',
            'project_sales_order',
            'project_invoice_dp',
            'work_order',
            'assignee',
            'doc_type'
        ));

        return view($view, compact(
            'project_survey',
            'project_offer',
            'project_sales_order',
            'project_invoice_dp',
            'work_order',
            'assignee',
            'doc_type',
            'tab',
            'table'
        ));
    }

    public function show(Request $request, Project $project)
    {
        $work_type = Work_type::all();
        $assignee = $request->assignee;
        $doc_type = $request->doc_type;
        return view('task_board.show', compact('project', 'work_type', 'assignee', 'doc_type'));
    }

    /**
     * Pre Sales
     */
    public function take_survey(Request $request, Project_survey $project_survey)
    {
        DB::beginTransaction();
        try {
            if ($project_survey->user_id != null) {
                return view('error', [
                    'message' => "Task has been picked up."
                ]);
            }
            $survey = Project_survey::where('id', $project_survey->id)->lockForUpdate()->first();
            $survey->update([
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

    public function continue_survey(Request $request, Project_survey $project_survey)
    {
        DB::beginTransaction();
        try {
            $project_survey->update([
                'projsur_status' => "Started",
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
                'projsur_status' => "Done",
                'projsur_finished_at' => Carbon::now(),
            ]);
            $project_offer = Project_offer::where('project_id', $project_survey->project_id)
                ->latest()
                ->first();
            if ($project_offer->projoff_status == 'Done') {
                Project_sales_order::create([
                    'project_id' => $project_survey->project_id,
                    'projso_number' => HelperController::generate_code("Sales Admin - Sales Order"),
                    'projso_status' => 'Open'
                ]);
                Project::find($project_survey->project_id)->update([
                    'project_status' => 'Sales Order'
                ]);
            }
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
            $file_upload = new File_upload();
            $file_upload->file_doc_type = $request->file_doc_type;
            $file_upload->file_table = 'project_survey';
            $file_upload->file_table_id = $project_survey->id;
            if ($request->has('file_upload')) {
                $file = $request->file('file_upload');
                $file_real_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $file_directory = "presales";
                $file_name = Str::random(24) . "." . $file_ext;
                Storage::disk('local')->put($file_directory . '/' . $file_name, file_get_contents($request->file('file_upload')->getRealPath()));
                $file_upload->file_directory = $file_directory;
                $file_upload->file_name = $file_name;
                $file_upload->file_real_name = $file_real_name;
                $file_upload->file_ext = $file_ext;
            }
            $file_upload->file_link = $request->file_link;
            $file_upload->save();
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
    public function take_offer(Request $request, Project_offer $project_offer)
    {
        DB::beginTransaction();
        try {
            if ($project_offer->user_id != null) {
                return view('error', [
                    'message' => "Task has been picked up."
                ]);
            }
            $offer = Project_offer::where('id', $project_offer->id)->lockForUpdate()->first();
            $offer->update([
                'user_id' => Auth::user()->id,
                'projoff_started_at' => Carbon::now(),
                'projoff_status' => "Started"
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'quotation'])->with([
                'status' => 'success',
                'message' => 'Data has been taken! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function hold_offer(Request $request, Project_offer $project_offer)
    {
        DB::beginTransaction();
        try {
            $project_offer->update([
                'projoff_status' => "Hold",
                'projoff_hold_message' => $request->message
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'quotation'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function continue_offer(Request $request, Project_offer $project_offer)
    {
        DB::beginTransaction();
        try {
            $project_offer->update([
                'projoff_status' => "Started",
                'projoff_hold_message' => $request->message
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'quotation'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function approval_offer(Request $request, Project_offer $project_offer)
    {
        DB::beginTransaction();
        try {
            $project_offer->update([
                'projoff_status' => "Approval"
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'quotation'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function finish_offer(Request $request, Project_offer $project_offer)
    {
        DB::beginTransaction();
        try {
            $project_offer->update([
                'projoff_offer_number' => $request->projoff_offer_number,
                'projoff_status' => "Done",
                'projoff_finished_at' => Carbon::now(),
            ]);
            $project_survey = Project_survey::where('project_id', $project_offer->project_id)
                ->latest()
                ->first();
            if ($project_survey->projsur_status == 'Done') {
                Project_sales_order::create([
                    'project_id' => $project_offer->project_id,
                    'projso_number' => HelperController::generate_code("Sales Admin - Sales Order"),
                    'projso_status' => 'Open'
                ]);
                Project::find($project_offer->project_id)->update([
                    'project_status' => 'Sales Order'
                ]);
            }
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'quotation'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function document_offer(Request $request, Project_offer $project_offer)
    {
        return view('task_board.document_offer', compact('project_offer'));
    }

    public function document_offer_update(Request $request, Project_offer $project_offer)
    {
        DB::beginTransaction();
        try {
            $file_upload = new File_upload();
            $file_upload->file_doc_type = $request->file_doc_type;
            $file_upload->file_table = 'project_offer';
            $file_upload->file_table_id = $project_offer->id;
            if ($request->has('file_upload')) {
                $request->validate([
                    'file_upload' => 'required|file|mimes:pdf,jpg,png|max:2048',
                ]);
                $file = $request->file('file_upload');
                $file_real_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $file_directory = "salesadmin";
                $file_name = Str::random(24) . "." . $file_ext;
                Storage::disk('local')->put($file_directory . '/' . $file_name, file_get_contents($request->file('file_upload')->getRealPath()));
                $file_upload->file_directory = $file_directory;
                $file_upload->file_name = $file_name;
                $file_upload->file_real_name = $file_real_name;
                $file_upload->file_ext = $file_ext;
            }
            $file_upload->file_link = $request->file_link;
            $file_upload->save();
            if ($request->file_doc_type == 'Sales Quotation') {
                $project_offer->projoff_quotation = 1;
            }
            $project_offer->save();
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'quotation'])->with([
                'status' => 'success',
                'message' => 'Data has been uploaded! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function take_sales_order(Request $request, Project_sales_order $project_sales_order)
    {
        DB::beginTransaction();
        try {
            if ($project_sales_order->user_id != null) {
                return view('error', [
                    'message' => "Task has been picked up."
                ]);
            }
            $project_sales_order = Project_sales_order::where('id', $project_sales_order->id)->lockForUpdate()->first();
            $project_sales_order->update([
                'user_id' => Auth::user()->id,
                'projso_started_at' => Carbon::now(),
                'projso_status' => "Started"
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'sales-order'])->with([
                'status' => 'success',
                'message' => 'Data has been taken! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function hold_sales_order(Request $request, Project_sales_order $project_sales_order)
    {
        DB::beginTransaction();
        try {
            $project_sales_order->update([
                'projso_status' => "Hold",
                'projso_hold_message' => $request->message
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'sales-order'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function continue_sales_order(Request $request, Project_sales_order $project_sales_order)
    {
        DB::beginTransaction();
        try {
            $project_sales_order->update([
                'projso_status' => "Started",
                'projso_hold_message' => $request->message
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'sales-order'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function approval_sales_order(Request $request, Project_sales_order $project_sales_order)
    {
        DB::beginTransaction();
        try {
            $project_sales_order->update([
                'projso_status' => "Approval"
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'sales-order'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function finish_sales_order(Request $request, Project_sales_order $project_sales_order)
    {
        DB::beginTransaction();
        try {
            $project_sales_order->update([
                'projso_so_number' => $request->projso_so_number,
                'projso_status' => "Done",
                'projso_finished_at' => Carbon::now(),
            ]);
            Project_invoice_dp::create([
                'project_id' => $project_sales_order->project_id,
                'projinvdp_number' => HelperController::generate_code("Finance Accounting - Invoice DP"),
                'projinvdp_status' => 'Open'
            ]);
            Project::find($project_sales_order->project_id)->update([
                'proj_status' => 'Down Payment'
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'sales-order'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function document_sales_order(Request $request, Project_sales_order $project_sales_order)
    {
        return view('task_board.document_sales_order', compact('project_sales_order'));
    }

    public function document_sales_order_update(Request $request, Project_sales_order $project_sales_order)
    {
        DB::beginTransaction();
        try {
            $file_upload = new File_upload();
            $file_upload->file_doc_type = $request->file_doc_type;
            $file_upload->file_table = 'project_sales_order';
            $file_upload->file_table_id = $project_sales_order->id;
            if ($request->has('file_upload')) {
                $request->validate([
                    'file_upload' => 'required|file|mimes:pdf,jpg,png|max:2048',
                ]);
                $file = $request->file('file_upload');
                $file_real_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $file_directory = "salesadmin";
                $file_name = Str::random(24) . "." . $file_ext;
                Storage::disk('local')->put($file_directory . '/' . $file_name, file_get_contents($request->file('file_upload')->getRealPath()));
                $file_upload->file_directory = $file_directory;
                $file_upload->file_name = $file_name;
                $file_upload->file_real_name = $file_real_name;
                $file_upload->file_ext = $file_ext;
            }
            $file_upload->file_link = $request->file_link;
            $file_upload->save();
            if ($request->file_doc_type == 'Sales Order') {
                $project_sales_order->projso_sales_order = 1;
            }
            $project_sales_order->save();
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'sales-order'])->with([
                'status' => 'success',
                'message' => 'Data has been uploaded! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function take_workorder(Request $request, Project_survey $project_offer) {}

    public function create_work_order(Request $request, Project_invoice_dp $project_invoice_dp)
    {
        DB::beginTransaction();
        try {
            $project_invoice_dp->update([
                'create_wo_by' => Auth::user()->id,
                'projinvdp_create_wo' => 1
            ]);
            Work_order::create([
                'project_id' => $project_invoice_dp->project_id,
                'wo_number' => HelperController::generate_wo_number(),
                'wo_date' => Carbon::now(),
                'created_by' => Auth::user()->id
            ]);
            Project::find($project_invoice_dp->project_id)->update([
                'proj_permit_wo' => 1,
                'proj_status' => 'Work Order'
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin', 'doc_type' => 'work-order'])->with([
                'status' => 'success',
                'message' => 'Data has been taken! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    /**
     * Finance & Accounting
     */
    //Invoice DP
    public function take_invoice_dp(Request $request, Project_invoice_dp $project_invoice_dp)
    {
        DB::beginTransaction();
        try {
            if ($project_invoice_dp->user_id != null) {
                return view('error', [
                    'message' => "Task has been picked up."
                ]);
            }
            $project_invoice_dp = Project_invoice_dp::where('id', $project_invoice_dp->id)->lockForUpdate()->first();
            $project_invoice_dp->update([
                'user_id' => Auth::user()->id,
                'projinvdp_started_at' => Carbon::now(),
                'projinvdp_status' => "Started"
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice-dp'])->with([
                'status' => 'success',
                'message' => 'Data has been taken! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function hold_invoice_dp(Request $request, Project_invoice_dp $project_invoice_dp)
    {
        DB::beginTransaction();
        try {
            $project_invoice_dp->update([
                'projinvdp_status' => "Hold",
                'projinvdp_hold_message' => $request->message
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice-dp'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function continue_invoice_dp(Request $request, Project_invoice_dp $project_invoice_dp)
    {
        DB::beginTransaction();
        try {
            $project_invoice_dp->update([
                'projinvdp_status' => "Started",
                'projinvdp_hold_message' => $request->message
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice-dp'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function approval_invoice_dp(Request $request, Project_invoice_dp $project_invoice_dp)
    {
        DB::beginTransaction();
        try {
            $project_invoice_dp->update([
                'projinvdp_status' => "Approval"
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice-dp'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function finish_invoice_dp(Request $request, Project_invoice_dp $project_invoice_dp)
    {
        DB::beginTransaction();
        try {
            $project_invoice_dp->update([
                'projinvdp_invoice_number' => $request->projso_so_number,
                'projinvdp_status' => "Done",
                'projinvdp_finished_at' => Carbon::now(),
            ]);
            if ($project_invoice_dp->projinvdp_permit_wo == 0) {
                $project_invoice_dp->projinvdp_permit_wo = 1;
                $project_invoice_dp->projinvdp_permit_at = Carbon::now();
                $project_invoice_dp->permit_by = Auth::user()->id;
            }
            $project_invoice_dp->save();
            $project = Project::find($project_invoice_dp->project_id);
            if ($project->proj_permit_wo == 0) {
                $project->proj_permit_wo = 1;
            }
            $project->save();
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice-dp'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function document_invoice_dp(Request $request, Project_invoice_dp $project_invoice_dp)
    {
        return view('task_board.document_invoice_dp', compact('project_invoice_dp'));
    }

    public function document_invoice_dp_update(Request $request, Project_invoice_dp $project_invoice_dp)
    {
        DB::beginTransaction();
        try {
            $file_upload = new File_upload();
            $file_upload->file_doc_type = $request->file_doc_type;
            $file_upload->file_table = 'project_invoice_dp';
            $file_upload->file_table_id = $project_invoice_dp->id;
            if ($request->has('file_upload')) {
                $file = $request->file('file_upload');
                $file_real_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $file_directory = "accounting";
                $file_name = Str::random(24) . "." . $file_ext;
                Storage::disk('local')->put($file_directory . '/' . $file_name, file_get_contents($request->file('file_upload')->getRealPath()));
                $file_upload->file_directory = $file_directory;
                $file_upload->file_name = $file_name;
                $file_upload->file_real_name = $file_real_name;
                $file_upload->file_ext = $file_ext;
            }
            $file_upload->file_link = $request->file_link;
            $file_upload->save();
            $project_invoice_dp->projinvdp_invoice = 1;
            $project_invoice_dp->save();
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice-dp'])->with([
                'status' => 'success',
                'message' => 'Data has been uploaded! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function permit_to_wo(Request $request, Project_invoice_dp $project_invoice_dp)
    {
        DB::beginTransaction();
        try {
            if ($project_invoice_dp->projinvdp_permit_wo == 1) {
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Work Order data is available!'
                ]);
            }
            $project_invoice_dp->projinvdp_permit_wo = 1;
            $project_invoice_dp->projinvdp_permit_at = Carbon::now();
            $project_invoice_dp->permit_by = Auth::user()->id;
            $project_invoice_dp->save();
            $project = Project::find($project_invoice_dp->project_id);
            if ($project->proj_permit_wo == 0) {
                $project->proj_permit_wo = 1;
            }
            $project->save();
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice-dp'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    //Invoice
    public function take_invoice(Request $request, Project_invoice $project_invoice)
    {
        DB::beginTransaction();
        try {
            if ($project_invoice->user_id != null) {
                return view('error', [
                    'message' => "Task has been picked up."
                ]);
            }
            $project_invoice = Project_invoice::where('id', $project_invoice->id)->lockForUpdate()->first();
            $project_invoice->update([
                'user_id' => Auth::user()->id,
                'projinv_started_at' => Carbon::now(),
                'projinv_status' => "Started"
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice'])->with([
                'status' => 'success',
                'message' => 'Data has been taken! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function hold_invoice(Request $request, Project_invoice $project_invoice)
    {
        DB::beginTransaction();
        try {
            $project_invoice->update([
                'projinv_status' => "Hold",
                'projinv_hold_message' => $request->message
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function continue_invoice(Request $request, Project_invoice $project_invoice)
    {
        DB::beginTransaction();
        try {
            $project_invoice->update([
                'projinv_status' => "Started",
                'projinv_hold_message' => $request->message
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function approval_invoice(Request $request, Project_invoice $project_invoice)
    {
        DB::beginTransaction();
        try {
            $project_invoice->update([
                'projinv_status' => "Approval"
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function finish_invoice(Request $request, Project_invoice $project_invoice)
    {
        DB::beginTransaction();
        try {
            $project_invoice->update([
                'projinv_invoice_number' => $request->projinv_invoice_number,
                'projinv_status' => "Done",
                'projinv_finished_at' => Carbon::now(),
            ]);
            $project = Project::find($project_invoice->project_id);
            $project->update([
                'proj_status' => "Paid"
            ]);
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function document_invoice(Request $request, Project_invoice $project_invoice)
    {
        return view('task_board.document_invoice_dp', compact('project_invoice_dp'));
    }

    public function document_invoice_update(Request $request, Project_invoice $project_invoice)
    {
        DB::beginTransaction();
        try {
            $file_upload = new File_upload();
            $file_upload->file_doc_type = $request->file_doc_type;
            $file_upload->file_table = 'project_invoice';
            $file_upload->file_table_id = $project_invoice->id;
            if ($request->has('file_upload')) {
                $file = $request->file('file_upload');
                $file_real_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $file_directory = "accounting";
                $file_name = Str::random(24) . "." . $file_ext;
                Storage::disk('local')->put($file_directory . '/' . $file_name, file_get_contents($request->file('file_upload')->getRealPath()));
                $file_upload->file_directory = $file_directory;
                $file_upload->file_name = $file_name;
                $file_upload->file_real_name = $file_real_name;
                $file_upload->file_ext = $file_ext;
            }
            $file_upload->file_link = $request->file_link;
            $file_upload->save();
            $project_invoice->projinvdp_invoice = 1;
            $project_invoice->save();
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice'])->with([
                'status' => 'success',
                'message' => 'Data has been uploaded! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    /**
     * Operation
     */
    public function take_work_order(Request $request, Project_work_order $project_work_order)
    {
        DB::beginTransaction();
        try {
            if ($project_work_order->user_id != null) {
                return view('error', [
                    'message' => "Task has been picked up."
                ]);
            }
            $project_work_order = Project_work_order::where('id', $project_work_order->id)->lockForUpdate()->first();
            $project_work_order->update([
                'user_id' => Auth::user()->id,
                'projwo_started_at' => Carbon::now(),
                'projwo_status' => "Started"
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'operation', 'doc_type' => 'work-order'])->with([
                'status' => 'success',
                'message' => 'Data has been taken! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function hold_work_order(Request $request, Project_work_order $project_work_order)
    {
        DB::beginTransaction();
        try {
            $project_work_order->update([
                'projwo_status' => "Hold",
                'projwo_hold_message' => $request->message
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'operation', 'doc_type' => 'work-order'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function continue_work_order(Request $request, Project_work_order $project_work_order)
    {
        DB::beginTransaction();
        try {
            $project_work_order->update([
                'projwo_status' => "Started",
                'projwo_hold_message' => $request->message
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'operation', 'doc_type' => 'work-order'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function approval_work_order(Request $request, Project_work_order $project_work_order)
    {
        DB::beginTransaction();
        try {
            $project_work_order->update([
                'projwo_status' => "Approval"
            ]);
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'operation', 'doc_type' => 'work-order'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function finish_work_order(Request $request, Project_work_order $project_work_order)
    {
        DB::beginTransaction();
        try {
            $project_work_order->update([
                'projwo_wo_number' => $request->projwo_wo_number,
                'projwo_status' => "Done",
                'projwo_finished_at' => Carbon::now(),
            ]);
            $project = Project::find($project_work_order->project_id);
            $project->update([
                'proj_status' => "Paid"
            ]);
            return redirect()->route('task_board.index', ['assignee' => 'operation', 'doc_type' => 'work-order'])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function document_work_order(Request $request, Project_work_order $project_work_order)
    {
        return view('task_board.document_work_order', compact('project_work_order'));
    }

    public function document_work_order_update(Request $request, Project_work_order $project_work_order)
    {
        DB::beginTransaction();
        try {
            $file_upload = new File_upload();
            $file_upload->file_doc_type = $request->file_doc_type;
            $file_upload->file_table = 'project_work_order';
            $file_upload->file_table_id = $project_work_order->id;
            if ($request->has('file_upload')) {
                $file = $request->file('file_upload');
                $file_real_name = $file->getClientOriginalName();
                $file_ext = $file->getClientOriginalExtension();
                $file_directory = "operation";
                $file_name = Str::random(24) . "." . $file_ext;
                Storage::disk('local')->put($file_directory . '/' . $file_name, file_get_contents($request->file('file_upload')->getRealPath()));
                $file_upload->file_directory = $file_directory;
                $file_upload->file_name = $file_name;
                $file_upload->file_real_name = $file_real_name;
                $file_upload->file_ext = $file_ext;
            }
            $file_upload->file_link = $request->file_link;
            $file_upload->save();
            $project_work_order->projinvdp_invoice = 1;
            $project_work_order->save();
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => 'operation', 'doc_type' => 'work-order'])->with([
                'status' => 'success',
                'message' => 'Data has been uploaded! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function create_assignment(Request $request, Project_work_order $project_work_order) {}

    /**
     * Download file
     */
    public function document_download(Request $request, File_upload $file_upload)
    {
        $filePath = $file_upload->file_directory . '/' . $file_upload->file_name;

        if (!Storage::disk('local')->exists($filePath)) {
            return view('error', compact('File not found'));
        }
        return Storage::download($file_upload->file_directory . '/' . $file_upload->file_name, $file_upload->file_real_name);
    }

    /**
     * Remove file
     */
    public function document_remove(Request $request, File_upload $file_upload)
    {
        DB::beginTransaction();
        try {
            $doc_type = '';
            $file_table = $file_upload->file_table;
            $file_table_id = $file_upload->file_table_id;
            $file_doc_type = $file_upload->file_doc_type;
            $filePath = $file_upload->file_directory . '/' . $file_upload->file_name;
            if (!Storage::disk('local')->exists($filePath)) {
                return view('error', compact('File not found'));
            }
            Storage::delete($file_upload->file_directory . '/' . $file_upload->file_name);
            $file_upload->file_directory = null;
            $file_upload->file_name = null;
            $file_upload->file_real_name = null;
            $file_upload->file_ext = null;
            $file_upload->save();
            if ($file_upload->file_directory == null && $file_upload->file_link == null) {
                $file_upload->delete();
                $this->set_document_to_null($file_doc_type, $file_table, $file_table_id);
            }
            DB::commit();
            if ($file_table == 'project_survey') {
                $assignee = 'pre-sales';
                $doc_type = null;
            } elseif ($file_table == 'project_offer') {
                $assignee = 'sales-admin';
                $doc_type = 'quotation';
            } elseif ($file_table == 'project_sales_order') {
                $assignee = 'sales-admin';
                $doc_type = 'sales-order';
            } elseif ($file_table == 'project_wokr_order') {
                $assignee = 'sales-admin';
                $doc_type = 'work-order';
            } elseif ($file_table == 'assignment') {
                $assignee = 'operational';
                $doc_type = 'assignment';
            } elseif ($file_table == 'invoice_dp') {
                $assignee = 'finance-accounting';
                $doc_type = 'invoice-dp';
            } elseif ($file_table = 'invoice') {
                $assignee = 'finance-accounting';
                $doc_type = 'invoice';
            }
            return redirect()->route('task_board.show', ['project' => $file_table_id, 'assignee' => $assignee, 'doc_type' => $doc_type])->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function link_remove(Request $request, File_upload $file_upload)
    {
        DB::beginTransaction();
        try {
            $file_table = $file_upload->file_table;
            $file_table_id = $file_upload->file_table_id;
            $file_doc_type = $file_upload->file_doc_type;
            $file_upload->file_link = null;
            $file_upload->save();
            if ($file_upload->file_directory == null && $file_upload->file_link == null) {
                $file_upload->delete();
                $this->set_document_to_null($file_doc_type, $file_table, $file_table_id);
            }
            DB::commit();
            return redirect()->route('task_board.show', $file_table_id)->with([
                'status' => 'success',
                'message' => 'Data has been updated! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }


    /**
     * ini dipake kalau semua dokumen yang di upload sudah ke delete semua.
     */
    public function set_document_to_null(String $file_doc_type, String $file_table, String $file_table_id)
    {
        $file_total = File_upload::where('file_table', $file_table)
            ->where('file_doc_type', $file_doc_type)
            ->where('file_table_id', $file_table_id)
            ->count();

        if ($file_total == 0) {
            if ($file_table === 'project_survey') {
                $map = [
                    'Denah'          => 'projsur_denah',
                    'Shop Drawing'   => 'projsur_shop',
                    'SLD/Topology'   => 'projsur_sld',
                    'RAB/BOQ/Budget' => 'projsur_rab',
                    'Personil'       => 'projsur_personil',
                    'Schedule'       => 'projsur_schedule',
                ];
                if (isset($map[$file_doc_type])) {
                    $project_survey = Project_survey::find($file_table_id);
                    $project_survey->{$map[$file_doc_type]} = null;
                    $project_survey->save();
                }
            }

            if ($file_table === 'project_offer') {
                $map = [
                    'Sales Quotation' => [Project_offer::class, 'projoff_quotation'],
                    'Sales Order'     => [Project_sales_order::class, 'projso_sales_order'],
                ];
                if (isset($map[$file_doc_type])) {
                    [$model, $column] = $map[$file_doc_type];
                    $instance = $model::find($file_table_id);
                    $instance->{$column} = null;
                    $instance->save();
                }
            }
        }
    }

    /**
     * Buat hapus task. Hanya superadmin yang bisa
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $map = [
                'pre-sales' => [
                    'default' => Project_survey::class,
                ],
                'sales-admin' => [
                    'quotation'    => Project_offer::class,
                    'sales-order'  => Project_sales_order::class,
                    'work-order'   => Project_work_order::class,
                ],
                'finance-accounting' => [
                    'invoice-dp' => Project_invoice_dp::class,
                    'invoice'    => Project_invoice::class,
                ],
            ];
            $model = null;
            if (isset($map[$request->assignee])) {
                $models = $map[$request->assignee];

                if (isset($models[$request->doc_type])) {
                    $model = $models[$request->doc_type];
                } elseif (isset($models['default'])) {
                    $model = $models['default'];
                }
            }
            if ($model) {
                $model::find($request->id)?->delete();
            }
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => $request->assignee, 'doc_type' => $request->doc_type])->with([
                'status' => 'success',
                'message' => 'Data has been deleted!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }


    /**
     * Buat membatalkan task. Hanya superadmin yang bisa
     */
    public function cancel(Request $request)
    {
        DB::beginTransaction();
        try {
            $map = [
                'pre-sales' => [
                    'default' => [Project_survey::class, 'projsur_status'],
                ],
                'sales-admin' => [
                    'quotation'   => [Project_offer::class, 'projoff_status'],
                    'sales-order' => [Project_sales_order::class, 'projso_status'],
                    'work-order'  => [Project_work_order::class, 'projwo_status'],
                ],
                'finance-accounting' => [
                    'invoice-dp' => [Project_invoice_dp::class, 'projinvdp_status'],
                    'invoice'    => [Project_invoice::class, 'projinv_status'],
                ],
            ];
            $action = $map[$request->assignee][$request->doc_type]
                ?? $map[$request->assignee]['default']
                ?? null;

            if ($action) {
                [$model, $field] = $action;
                $model::find($request->id)?->update([
                    $field => 'Cancelled'
                ]);
            }
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => $request->assignee, 'doc_type' => $request->doc_type])->with([
                'status' => 'success',
                'message' => 'Data has been deleted!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }
}
