<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\File_upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customer = Customer::where('cust_name', 'like', '%' . $request->search . '%');
        if ($request->cust_type && $request->cust_type != 'All') {
            $project = $customer->where('cust_type', $request->cust_type);
        }
        $show = 10;
        if ($request->show && $request->show != '5') {
            $show = $request->show;
        }
        $customer = $customer->paginate($show, ['*'], 'page', $request->page ?? 1)
            ->onEachSide(0)
            ->appends(request()->except('page'));
        return view('customer.index', compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = [
            'cust_name' => 'required|unique:customers,cust_name',
            'cust_address' => 'required'
        ];
        foreach (['file_ktp', 'file_nib', 'file_npwp'] as $field) {
            if ($request->hasFile($field)) {
                $validate[$field] = 'file|mimes:jpg,jpeg,png,pdf|max:2048';
            }
        }
        $request->validate($validate);
        DB::beginTransaction();
        try {
            $data = array_merge($request->except('_token', '_method'));
            $customer = Customer::create($data);
            if ($request->has('file_ktp')) {
                $this->save_file('KTP', 'file_ktp',  $request, $customer);
            }
            if ($request->has('file_nib')) {
                $this->save_file('NIB', 'file_nib',  $request, $customer);
            }
            if ($request->has('file_npwp')) {
                $this->save_file('NPWP', 'file_npwp',  $request, $customer);
            }
            DB::commit();
            return redirect()->route('customer.index')->with([
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
    public function show(Customer $customer)
    {
        return view('customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validate = [
            'cust_name' => 'required|unique:customers,cust_name,' . $customer->id . ',id',
            'cust_address' => 'required'
        ];
        foreach (['file_ktp', 'file_nib', 'file_npwp'] as $field) {
            if ($request->hasFile($field)) {
                $validate[$field] = 'file|mimes:jpg,jpeg,png,pdf|max:2048';
            }
        }
        $request->validate($validate);
        DB::beginTransaction();
        try {
            $data = array_merge($request->except('_token', '_method'));
            $customer->update($data);
            if ($request->has('file_ktp')) {
                $this->save_file('KTP', 'file_ktp',  $request, $customer);
            }
            if ($request->has('file_nib')) {
                $this->save_file('NIB', 'file_nib',  $request, $customer);
            }
            if ($request->has('file_npwp')) {
                $this->save_file('NPWP', 'file_npwp',  $request, $customer);
            }
            DB::commit();
            return redirect()->route('customer.index')->with([
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
    public function destroy(Customer $customer)
    {
        DB::beginTransaction();
        try {
            $customer->delete();
            $file_upload = File_upload::where('file_table', 'customer')
                ->where('file_table_id', $customer->id)
                ->get();
            if ($file_upload) {
                foreach ($file_upload as $d) {
                    $filePath = $d->file_directory . '/' . $d->file_name;
                    if (!Storage::disk('local')->exists($filePath)) {
                        continue;
                    }
                    File_upload::find($d->id)->delete();
                    Storage::delete($filePath);
                }
            }
            DB::commit();
            return redirect()->route('customer.index')->with([
                'status' => 'success',
                'message' => 'Data has been deleted!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }

    public function save_file(String $type, String $req_type, Request $request, Customer $customer)
    {
        $file_upload_delete = File_upload::where('file_table', 'customer')
            ->where('file_table_id', $customer->id)
            ->where('file_doc_type', $type)
            ->get();
        if ($file_upload_delete->count() > 0) {
            $filePath = $file_upload_delete->file_directory . '/' . $file_upload_delete->file_name;
            File_upload::where('file_table', 'customer')
                ->where('file_table_id' . $customer->id)
                ->where('file_doc_type', $type)
                ->delete();
            if (Storage::disk('local')->exists($filePath)) {
                Storage::delete($filePath);
            }
        }
        $file_upload = new File_upload();
        $file_upload->file_doc_type = $type;
        $file_upload->file_table = 'customer';
        $file_upload->file_table_id = $customer->id;
        $file = $request->file($req_type);
        $file_real_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $file_directory = "customer";
        $file_name = Str::random(24) . "." . $file_ext;
        Storage::disk('local')->put($file_directory . '/' . $file_name, file_get_contents($request->file($req_type)->getRealPath()));
        $file_upload->file_directory = $file_directory;
        $file_upload->file_name = $file_name;
        $file_upload->file_real_name = $file_real_name;
        $file_upload->file_ext = $file_ext;
        $file_upload->save();
    }

    public function file_download(Request $request, File_upload $file_upload)
    {
        $filePath = $file_upload->file_directory . '/' . $file_upload->file_name;

        if (!Storage::disk('local')->exists($filePath)) {
            return view('error', compact('File not found'));
        }
        return Storage::download($filePath, $file_upload->file_real_name);
    }

    public function file_remove(Request $request, File_upload $file_upload)
    {
        DB::beginTransaction();
        try {
            $filePath = $file_upload->file_directory . '/' . $file_upload->file_name;
            if (!Storage::disk('local')->exists($filePath)) {
                return view('error', compact('File not found'));
            }
            Storage::delete($filePath);
            $file_upload->delete();
            DB::commit();
            return redirect()->route('customer.index')->with([
                'status' => 'success',
                'message' => 'Data has been deleted!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return view('error', compact('th'));
        }
    }
}
