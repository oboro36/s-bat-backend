<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Http\Models\SiteModel as SiteModel;

class VideoSearchController extends Controller
{
    public function getVideoSiteOption()
    {
        // $sites = DB::table('MST_SITE')->select('SITE AS index', 'SITE AS value')->get();

        //Eloquent testing with Model 
        $sites = SiteModel::select('SITE AS index', 'SITE AS value')->get();

        return [
            'sites' => $sites,
        ];
    }
    public function getVideoOtherOption()
    {
        $payload = file_get_contents('php://input');
        $JSdata = json_decode($payload, true); // to Array (if 'false' -> to stdClass)
    
        $site = $JSdata['site'];

        $programs = DB::table('MST_PROGRAM')->select('PROGRAM_NO AS index', 'PROGRAM AS value')->where('SITE',$site)->get();
        $lines = DB::table('MST_LINE')->select('LINE AS index', 'LINE AS value')->where('SITE',$site)->get();
        $maintcontents = DB::table('MST_MAINT_CONTENTS')->select('MAINT_CONTENTS_CODE AS index', 'MAINT_CONTENTS AS value')->where('SITE',$site)->get();

        return [
            'programs' => $programs,
            'lines' => $lines,
            'maintcontents' => $maintcontents
        ];
    }
    public function getVideoData()
    {
        $payload = file_get_contents('php://input');
        $JSdata = json_decode($payload, true); // to Array (if 'false' -> to stdClass)

        $machine = 1;
    
        $choice1 = $JSdata['choice1'];
        $choice2 = $JSdata['choice2'];

        $choice1_res = DB::table('TBL_RESULT_HEADER')->select(
            'TBL_RESULT_IMAGE.CHAMBER_CODE',
            'TBL_RESULT_IMAGE.POSITION',
            'TBL_RESULT_IMAGE.IMAGE_DIRECTORY',
            'TBL_RESULT_IMAGE.MOVIE_DIRECTORY'
        )
        ->where('SITE', $choice1['site'])
        ->where('PROGRAM_NO', $choice1['program'])
        ->where('LINE', $choice1['line'])
        ->where('MAINT_CONTENTS_CODE', $choice1['content'])
        ->where('MACHINE_CODE', $machine)
        ->leftJoin('TBL_RESULT_IMAGE','TBL_RESULT_HEADER.RESULT_NO','=','TBL_RESULT_IMAGE.RESULT_NO')
        ->get();

        $choice2_res = DB::table('TBL_RESULT_HEADER')->select(
            'TBL_RESULT_IMAGE.CHAMBER_CODE',
            'TBL_RESULT_IMAGE.POSITION',
            'TBL_RESULT_IMAGE.IMAGE_DIRECTORY',
            'TBL_RESULT_IMAGE.MOVIE_DIRECTORY'
        )
        ->where('SITE', $choice2['site'])
        ->where('PROGRAM_NO', $choice2['program'])
        ->where('LINE', $choice2['line'])
        ->where('MAINT_CONTENTS_CODE', $choice2['content'])
        ->where('MACHINE_CODE', $machine)
        ->leftJoin('TBL_RESULT_IMAGE','TBL_RESULT_HEADER.RESULT_NO','=','TBL_RESULT_IMAGE.RESULT_NO')
        ->get();

        $pack = [
            'choice1' => $choice1_res,
            'choice2' => $choice2_res
        ];

        return $pack;
    }
}
