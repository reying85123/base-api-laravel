<?php

use Illuminate\Database\Migrations\Migration;

class SetupCommonAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::beginTransaction();

        $file_path = resource_path('system_data/address.json');
        if(!file_exists($file_path)){
            abort(400, '找不到初始化檔案');
        }

        //讀取縣市地區檔案        
        $address_data = collect(json_decode(file_get_contents($file_path)));

        $address_data = $address_data->groupBy('City');

        $city_list = $address_data->keys();

        //1.建立縣市
        \DB::table('commons')->insert(
            $city_list->map(function($city){
                return [
                    'common_group' => 'Address',
                    'name' => $city,
                ];
            })->toArray()
        );

        //2.建立地區
        $area_list = [];
        $city_list->flip()->each(function($index, $city) use ($address_data, &$area_list){
            array_push($area_list, ...$address_data[$city]->unique('Area')->map(function($address) use ($index){
                return [
                    'common_group' => 'Address',
                    'name' => $address->Area,
                    'parent_id' => ++$index,
                    'value' => json_encode(['post_code' => mb_substr($address->Zip5, 0, 3)]),
                ];
            })->toArray());
        });

        \DB::table('commons')->insert($area_list);

        \DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('commons')->truncate();
    }
}
