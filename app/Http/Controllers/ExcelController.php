<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TennisRankingsImport;

class ExcelController extends Controller
{
    public function import()
    {
        Excel::import(new TennisRankingsImport,'Tennis_Rankings_2019.csv' );

        return redirect('/');
    }
}
