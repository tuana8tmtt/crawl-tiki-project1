<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\Crawler;
use DB;
class Crawler_sp extends Controller
{
    //
    public static function crawl_shopee($keyword, $quantity)
    {

        //    $keyword_query = DB::table('keywords')->where('is_crawl', 0)->first();
        //    $keyword = $keyword_query->keyword;
        //    DB::table('keywords')
        //        ->where('Keyword', $keyword)
        //        ->update(['is_crawl' => 1]);
            $crawler = new Crawler($keyword, $quantity);
            dispatch($crawler);
            return true;

    }
}
