<?php

namespace App\Http\Controllers;

use App\Models\Document_number;
use Illuminate\Http\Request;

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
            ->orderBy('id')
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
        $number = $project_number . '-' . $year . '-' . str_pad($prefix, 3, '0', STR_PAD_LEFT);
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
}
