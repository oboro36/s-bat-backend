<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VideoSearchController extends Controller
{
    public function getSiteDropdown(Request $request)
    {
        $sites = DB::table('MST_SITE')->select('SITE AS id','SITE AS value')->get();
        // $programs = DB::table('MST_PROGRAM')->select('PROGRAM_NO AS id','PROGRAM AS value')->get();
        // $lines = DB::table('MST_PROGRAM')->select('LINE AS id','LINE AS value')->get();

        return $sites;
    }
}
