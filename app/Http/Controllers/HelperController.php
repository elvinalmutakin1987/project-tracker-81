<?php

namespace App\Http\Controllers;

use App\Models\Document_number;
use App\Models\File_upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class HelperController extends Controller
{
    public static function generate_project_number(String $document, String $project_name, String $project_number, String $type)
    {
        $date = date('Y-m-d');
        $day = date('d', strtotime($date));
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $document_number = Document_number::where('document', $document)
            ->where('year', date('Y'))
            ->latest()
            ->first();
        if (!$document_number) {
            $document_number = new Document_number();
            $prefix = 1;
        } else {
            $prefix = (int) $document_number->prefix + 1;
        }
        $words = preg_split('/\s+|-/', $project_name);
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }
        $number = $project_number; // . '-' . $year . '-' . str_pad($prefix, 3, '0', STR_PAD_LEFT);
        if ($type == 'auto') {
            $number = $initials . '-' . $year . '-' . str_pad($prefix, 3, '0', STR_PAD_LEFT);
        }
        $document_number->document = $document;
        $document_number->number = $number;
        $document_number->date = $date;
        $document_number->prefix = $prefix;
        $document_number->day = $day;
        $document_number->month = $month;
        $document_number->year = $year;
        $document_number->save();
        return $number;
    }

    public function get_file_pdf(Request $request)
    {
        $file_upload = File_upload::find($request->file_upload_id);
        $pathFile = storage_path('app/' . $file_upload->file_directory . '/' . $file_upload->file_name);
        return Response::file($pathFile, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function get_file_image(Request $request)
    {
        $file_upload = File_upload::find($request->file_upload_id);
        $pathFile = storage_path('app/' . $file_upload->file_directory . '/' . $file_upload->file_name);
        return Response::file($pathFile, [
            'Content-Type' => 'image/jpeg',
        ]);
    }

    public static function generate_code(String $document)
    {
        $initials = '';
        $date = date('Y-m-d');
        $day = date('d', strtotime($date));
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $document_number = Document_number::where('document', $document)
            ->where('year', date('Y'))
            ->latest()
            ->first();
        if (!$document_number) {
            $document_number = new Document_number();
            $prefix = 1;
        } else {
            $prefix = (int) $document_number->prefix + 1;
        }
        if ($document == 'Pre Sales') {
            $initials = '1';
        } elseif ($document == 'Sales Admin - Quotation') {
            $initials = '2-1';
        } elseif ($document == 'Sales Admin - Sales Order') {
            $initials = '2-2';
        } elseif ($document == 'Sales Admin - Work Order') {
            $initials = '2-3';
        } elseif ($document == 'Finance Accounting - Invoice DP') {
            $initials = '3-1';
        } elseif ($document == 'Finance Accounting - Invoice') {
            $initials = '3-2';
        } elseif ($document == 'Operation - Work Order') {
            $initials = '4';
        }
        $number = $initials . '-' . str_pad($prefix, 4, '0', STR_PAD_LEFT);
        $document_number->document = $document;
        $document_number->number = $number;
        $document_number->date = $date;
        $document_number->prefix = $prefix;
        $document_number->day = $day;
        $document_number->month = $month;
        $document_number->year = $year;
        $document_number->save();
        return $number;
    }

    public static function generate_wo_number()
    {
        $date = date('Y-m-d');
        $day = date('d', strtotime($date));
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $document_number = Document_number::where('document', 'Work Order')
            ->where('year', date('Y'))
            ->latest()
            ->first();
        if (!$document_number) {
            $document_number = new Document_number();
            $prefix = 1;
        } else {
            $prefix = (int) $document_number->prefix + 1;
        }
        $number = 'WO/' . $year . '/' . $month . '/' . str_pad($prefix, 3, '0', STR_PAD_LEFT);
        $document_number->document = 'Work Order';
        $document_number->number = $number;
        $document_number->date = $date;
        $document_number->prefix = $prefix;
        $document_number->day = $day;
        $document_number->month = $month;
        $document_number->year = $year;
        $document_number->save();
        return $number;
    }
}
