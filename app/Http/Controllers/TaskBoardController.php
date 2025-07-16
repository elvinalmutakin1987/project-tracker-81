<?php

namespace App\Http\Controllers;

use App\Models\File_upload;
use App\Models\Project;
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
        }
        $doc_type = "quotation";
        if ($request->has('doc_type')) {
            $doc_type = $request->doc_type;
        }
        $show = 10;
        if ($request->show && $request->show != '5') {
            $show = $request->show;
        }
        /**
         * Survey
         */
        $project_survey = Project_survey::select('project_surveys.*', 'projects.proj_number')
            ->leftJoin('projects', 'projects.id', '=', 'project_surveys.project_id')
            ->where('proj_number', 'like', '%' . $request->search . '%');
        if ($request->status && $request->status != 'All') {
            $project_survey = $project_survey->where('projsur_status', $request->status);
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
        $project_sales_order = $project_sales_order->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));

        if ($assignee == 'pre-sales') {
            $view = 'task_board.pre_sales';
            $table = view('task_board.table_survey', compact('project_survey', 'assignee'));
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
            $view = 'task_board.sales_admin';
            if ($doc_type == 'quotation') {
                $table = view('task_board.table_offer', compact('project_offer', 'assignee'));
            }
            if ($doc_type == 'sales-order') {
                $table = view('task_board.table_so', compact('project_sales_order', 'assignee'));
            }
            if ($doc_type == 'work-order') {
                $table = view('task_board.table_wo', compact('project_work_order', 'assignee'));
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
        if ($assignee == 'operation') {
            $view = 'task_board.operation';
            $table = view('task_board.table_operation', compact('project_operation', 'assignee'));
            /**
             * cek hak akses
             */
            if (!Auth::user()->hasPermissionTo('task_board.operation')) {
                return view('error', [
                    'message' => "You’re not authorized to view this page."
                ]);
            }
        }
        if ($assignee == 'finance-accounting') {
            /**
             * cek hak akses
             */
            if (!Auth::user()->hasPermissionTo('task_board.finance-accounting')) {
                return view('error', [
                    'message' => "You’re not authorized to view this page."
                ]);
            }
            $view = 'task_board.finance_accounting';
        }
        $tab = view('task_board.tab', compact('project_survey', 'project_offer', 'project_sales_order', 'assignee'));
        return view($view, compact('project_survey', 'project_offer', 'project_sales_order', 'assignee', 'tab', 'table'));
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
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin'])->with([
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
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin'])->with([
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
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin'])->with([
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
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin'])->with([
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
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin'])->with([
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
            return redirect()->route('task_board.index', ['assignee' => 'sales-admin'])->with([
                'status' => 'success',
                'message' => 'Data has been uploaded! '
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }


    public function take_salesorder(Request $request, Project_survey $project_offer) {}

    public function take_workorder(Request $request, Project_survey $project_offer) {}

    /**
     * Operation
     */

    /**
     * Finance & Accounting
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
            } elseif ($file_table == 'project_offer' || $file_table == 'project_sales_order') {
                $assignee = 'sales-admin';
            } elseif ($file_table == 'operational') {
                $assignee = 'operational';
            } elseif ($file_table == 'invoice_dp' || $file_table == 'invoice') {
                $assignee = 'finance-accounting';
            }
            return redirect()->route('task_board.show', ['project' => $file_table_id, 'assignee' => $assignee])->with([
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
                Project_offer::find($request->id)->delete();
            }
            DB::commit();
            return redirect()->route('task_board.index', ['assignee' => $request->assignee])->with([
                'status' => 'success',
                'message' => 'Data has been deleted!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }
}
