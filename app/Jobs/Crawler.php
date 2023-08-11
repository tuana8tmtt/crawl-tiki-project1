<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class Crawler implements ShouldQueue
{
    public $keyword;
    public $count;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($keyword, $count)
    {
        //
        $this->keyword = $keyword;
        $this->count = $count;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        print($this->count / 100);
        for($i = 0; $i < ($this->count/100); $i++){
            $url_search_shoppe = 'https://tiki.vn/api/v2/products?limit=100&page=' . ($i + 1) . '&aggregations=2&trackity_id=23123123&q=' . urlencode($this->keyword) . '';
            $data_response = json_decode(file_get_contents($url_search_shoppe));
            $items = $data_response->data;
            for ($x = 0; $x<=100; $x++ ) {
                
                if($items != ''){
                    foreach ($items as $item) {
                        
                            $url = "https://tiki.vn/". $item->url_path;
                            DB::table('product')->insertOrIgnore([
                                [
                                    'shop_id'               => $item->seller_id,
                                    'item_id'               => $item->id,
                                    'keyword'               => $this->keyword,
                                    'url'                   => $url,
                                    'name'                  => $item->name,
                                    'image_cover'           => $item->thumbnail_url,
                                    'images'                => $item->thumbnail_url,
                                    'view_count'            => $item->review_count,
                                    'brand'                 => property_exists($item, 'brand_name') ? $item->brand_name : "",
                                    'price'                 => $item->price,
                                    'price_before_discount' => $item->original_price,
                                    'nature'                => "",
                                    'item_rating'           => $item->rating_average,
                                    'shop_location'         => "",
                                    'created_at'            => date("Y-m-d h:i:sa", time())
                                ],
                            ]);
                    }
                }else {

                    break;
                }

            }
        }
        DB::table('keywords')
            ->where('Keyword', $this->keyword)
            ->update(['is_crawl' => 1]);
    }
}
