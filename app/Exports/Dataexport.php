<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class Dataexport implements FromQuery, WithHeadings,WithMapping
{
 
    use Exportable; 

    public function __construct()
    {
        
    }


    public function map($order): array
    { 
        return [
            $order->id,
            $order->name,
            $order->description,
            'in stock',
            'new',
            $order->old_price,
            'https://loreenbd.com/product/'.$order->slug,
            'https://loreenbd.com/'.$order->image['image'],
            'loreenbd',
            '',
            '',
            '',
            $order->new_price,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''

        ];
         
    }

    public function query()
    { 
        
        return Product::where(['status' => 1])
            ->orderBy('id', 'DESC') 
            ->with('prosizes', 'procolors') ; 

    }


    public function headings(): array
    {
        return ["id", "title", "description", "availability", "condition", "price", "link", "image_link", "brand", "google_product_category", "fb_product_category", "quantity_to_sell_on_facebook", "sale_price", "sale_price_effective_date", "item_group_id", "gender", "color", "size", "age_group", "material", "pattern", "shipping", "shipping_weight", "video[0].url", "video[0].tag[0]", "gtin", "product_tags[0]", "product_tags[1]", "style[0]"]; 
    }



}
