<?php

namespace App\Http\Controllers;

use App\Models\File_upload;
use App\Models\Project;
use App\Models\Project_invoice_dp;
use App\Models\Project_offer;
use App\Models\Project_sales_order;
use App\Models\Project_survey;
use App\Models\Work_type;
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
            $docMap = [
                'quotation'    => [
                    'view' => 'task_board.table_offer',
                    'data' => 'project_offer'
                ],
                'sales-order'  => [
                    'view' => 'task_board.table_so',
                    'data' => 'project_sales_order'
                ],
                'work-order'   => [
                    'view' => 'task_board.table_wo',
                    'data' => 'project_work_order'
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
        /**
         * ================================================================================================
         */
        $tab = view('task_board.tab', compact(
            'project_survey',
            'project_offer',
            'project_sales_order',
            'project_invoice_dp',
            'assignee'
        ));
        return view($view, compact(
            'project_survey',
            'project_offer',
            'project_sales_order',
            'project_invoice_dp',
            'assignee',
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
            $project = Project::find($project_sales_order->project_id);
            $project->update([
                'proj_status' => 'Down Payment'
            ]);
            /**
             * Create task untuk finance accounting > invoice DP
             */
            Project_invoice_dp::create([
                'project_id' => $project_sales_order->project_id,
                'projinvdp_number' => HelperController::generate_code("Invoice DP"),
                'projinvdp_status' => 'Open'
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

    /**
     * Finance & Accounting
     */
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
                'projso_so_number' => $request->projso_so_number,
                'projso_status' => "Done",
                'projso_finished_at' => Carbon::now(),
            ]);
            $project = Project::find($project_invoice_dp->project_id);
            $project->update([
                'proj_permit_wo' => 1
            ]);
            /**
             * Create task untuk sales admin > work order
             */
            Project_work_order::create([
                'project_id' => $project_invoice_dp->project_id,
                'projinvdp_number' => HelperController::generate_code("Invoice DP"),
                'projinvdp_status' => 'Open'
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

    /**
     * Operation
     */

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
        if ($file_table == 'project_survey') {
            $file_total = File_upload::where('file_table', 'project_survey')
                ->where('file_table_id', $file_table_id)
                ->count();
            if ($file_total == 0) {
                $project_survey = Project_survey::find($file_table_id);
                if ($file_doc_type == 'Denah') {
                    $project_survey->projsur_denah = null;
                }
                if ($file_doc_type == 'Shop Drawing') {
                    $project_survey->projsur_shop = null;
                }
                if ($file_doc_type == 'SLD/Topology') {
                    $project_survey->projsur_sld = null;
                }
                if ($file_doc_type == 'RAB/BOQ/Budget') {
                    $project_survey->projsur_rab = null;
                }
                if ($file_doc_type == 'Personil') {
                    $project_survey->projsur_personil = null;
                }
                if ($file_doc_type == 'Schedule') {
                    $project_survey->projsur_schedule = null;
                }
                $project_survey->save();
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
            if ($request->assignee == 'pre-sales') {
                Project_survey::find($request->id)->delete();
            } elseif ($request->assignee == 'sales-admin') {
                if ($request->doc_type == 'quotation') {
                    Project_offer::find($request->id)->delete();
                } elseif ($request->doc_type == 'sales-order') {
                    Project_sales_order::find($request->id)->delete();
                } elseif ($request->doc_type == 'work-order') {
                }
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
            if ($request->assignee == 'pre-sales') {
                Project_survey::find($request->id)->delete();
            } elseif ($request->assignee == 'sales-admin') {
                if ($request->doc_type == 'quotation')
                    Project_offer::find($request->id)->update([
                        'projoff_status' => "Cancelled"
                    ]);
                elseif ($request->doc_type == 'sales-order') {
                    Project_sales_order::find($request->id)->update([
                        'projso_status' => "Cancelled"
                    ]);
                } elseif ($request->doc_type == 'work-order') {
                }
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
