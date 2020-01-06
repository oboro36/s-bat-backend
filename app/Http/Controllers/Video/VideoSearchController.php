<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

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
    public function getVideoProgramLineOption()
    {
        $payload = file_get_contents('php://input');
        $JSdata = json_decode($payload, true); // to Array (if 'false' -> to stdClass)

        $site = $JSdata['site'];

        $programs = DB::table('TBL_RESULT_HEADER AS RH')
            ->select('RH.PROGRAM_NO AS index', 'MP.PROGRAM AS value')
            ->leftJoin('MST_PROGRAM AS MP', function ($join) {
                $join->on('MP.SITE', '=', 'RH.SITE');
                $join->on('MP.PROGRAM_NO', '=', 'RH.PROGRAM_NO');
            })
            ->where('RH.SITE', $site)
            ->groupby('RH.PROGRAM_NO', 'MP.PROGRAM_NO', 'MP.PROGRAM')
            ->get();

        $lines = DB::table('TBL_RESULT_HEADER AS RH')
            ->select('RH.LINE AS index', 'RH.LINE AS value')
            ->where('RH.SITE', $site)
            ->groupby('RH.LINE')
            ->get();

        if (count($programs) == 0) {
            $response_code = 204;
        } else {
            $response_code = 200;
        }

        return  Response::json([
            'programs' => $programs,
            'lines' => $lines
        ], $response_code);
    }
    public function getVideoProgramOption()
    {
        $payload = file_get_contents('php://input');
        $JSdata = json_decode($payload, true); // to Array (if 'false' -> to stdClass)

        $site = $JSdata['site'];

        $programs = DB::table('TBL_RESULT_HEADER AS RH')
            ->select('RH.PROGRAM_NO AS index', 'MP.PROGRAM AS value')
            ->leftJoin('MST_PROGRAM AS MP', function ($join) {
                $join->on('MP.SITE', '=', 'RH.SITE');
                $join->on('MP.PROGRAM_NO', '=', 'RH.PROGRAM_NO');
            })
            ->where('RH.SITE', $site)
            ->groupby('RH.PROGRAM_NO', 'MP.PROGRAM_NO', 'MP.PROGRAM')
            ->get();

        if (count($programs) == 0) {
            $response_code = 204;
        } else {
            $response_code = 200;
        }

        return  Response::json([
            'programs' => $programs,
        ], $response_code);
    }
    public function getVideoLineOption()
    {
        $payload = file_get_contents('php://input');
        $JSdata = json_decode($payload, true); // to Array (if 'false' -> to stdClass)

        $site = $JSdata['site'];
        $program = $JSdata['program'];

        $lines = DB::table('TBL_RESULT_HEADER AS RH')
            ->select('RH.LINE AS index', 'RH.LINE AS value')
            ->where('RH.SITE', $site)
            ->where('RH.PROGRAM_NO', $program)
            ->groupby('RH.LINE')
            ->get();

        if (count($lines) == 0) {
            $response_code = 204;
        } else {
            $response_code = 200;
        }

        return  Response::json([
            'lines' => $lines,
        ], $response_code);
    }
    public function getVideoContentOption()
    {
        $payload = file_get_contents('php://input');
        $JSdata = json_decode($payload, true); // to Array (if 'false' -> to stdClass)

        $site = $JSdata['site'];
        $program = $JSdata['program'];
        $line = $JSdata['line'];

        $contents = DB::table('TBL_RESULT_HEADER AS RH')
            ->select('RH.MAINT_CONTENTS_CODE AS index', 'MM.MAINT_CONTENTS AS value')
            ->leftJoin('MST_MAINT_CONTENTS AS MM', function ($join) {
                $join->on('MM.SITE', '=', 'RH.SITE');
                $join->on('MM.MAINT_CONTENTS_CODE', '=', 'RH.MAINT_CONTENTS_CODE');
            })
            ->where('RH.SITE', $site)
            ->where('RH.PROGRAM_NO', $program)
            ->where('RH.LINE', $line)
            ->groupby('RH.MAINT_CONTENTS_CODE', 'MM.MAINT_CONTENTS_CODE', 'MM.MAINT_CONTENTS')
            ->get();

        if (count($contents) == 0) {
            $response_code = 204;
        } else {
            $response_code = 200;
        }

        return  Response::json([
            'contents' => $contents,
        ], $response_code);
    }
    public function getVideoAvailableDate()
    {
        $payload = file_get_contents('php://input');
        $JSdata = json_decode($payload, true); // to Array (if 'false' -> to stdClass)

        $cond = $JSdata['cond'];
        $content = $JSdata['content'];
        $machine = 1;

        $dates = DB::table('TBL_RESULT_HEADER AS RH')
            ->select(
                'RH.ANALYSIS_DATE',
                'RH.MAINT_CONTENTS_CODE'
            )
            ->where('RH.SITE', $cond['site'])
            ->where('RH.PROGRAM_NO', $cond['program'])
            ->where('RH.LINE', $cond['line'])
            ->where('RH.MACHINE_CODE', $machine);

        if ($content != null) {
            $dates = $dates->where('RH.MAINT_CONTENTS_CODE', $content);
        }

        $dates = $dates->get();

        if (count($dates) == 0) {
            $response_code = 204;
        } else {
            $response_code = 200;
        }

        return  Response::json([
            'dates' => $dates,
        ], $response_code);
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
            ->where('ANALYSIS_DATE', $choice1['analysisdate'])
            ->leftJoin('TBL_RESULT_IMAGE', 'TBL_RESULT_HEADER.RESULT_NO', '=', 'TBL_RESULT_IMAGE.RESULT_NO')
            ->orderBy('TBL_RESULT_IMAGE.CHAMBER_CODE', 'ASC')
            ->orderBy('TBL_RESULT_IMAGE.POSITION', 'ASC')
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
            ->where('ANALYSIS_DATE', $choice2['analysisdate'])
            ->leftJoin('TBL_RESULT_IMAGE', 'TBL_RESULT_HEADER.RESULT_NO', '=', 'TBL_RESULT_IMAGE.RESULT_NO')
            ->orderBy('TBL_RESULT_IMAGE.CHAMBER_CODE', 'ASC')
            ->orderBy('TBL_RESULT_IMAGE.POSITION', 'ASC')
            ->get();

        $pack = [
            'choice1' => $choice1_res,
            'choice2' => $choice2_res
        ];

        return $pack;
    }
    public function getChamberList()
    {
        $payload = file_get_contents('php://input');
        $JSdata = json_decode($payload, true); // to Array (if 'false' -> to stdClass)

        $site = $JSdata['site'];

        $chambers = DB::table('MST_CHAMBER AS MC')
            ->select('MC.CHAMBER_CODE')
            ->where('MC.SITE', $site)
            ->orderBy('MC.CHAMBER_CODE', 'ASC')
            ->get();

        if (count($chambers) == 0) {
            $response_code = 204;
        } else {
            $response_code = 200;
        }

        return  Response::json([
            'chambers' => $chambers,
        ], $response_code);
    }
    public function getMasterMaintContents()
    {
        $payload = file_get_contents('php://input');
        $JSdata = json_decode($payload, true); // to Array (if 'false' -> to stdClass)

        $site = $JSdata['site'];

        $contents = DB::table('MST_MAINT_CONTENTS')
            ->select('MAINT_CONTENTS_CODE AS code', 'MAINT_CONTENTS AS name')
            ->where('SITE', $site)
            ->get();

        if (count($contents) == 0) {
            $response_code = 204;
        } else {
            $response_code = 200;
        }

        return  Response::json([
            'contents' => $contents,
        ], $response_code);
    }
}
