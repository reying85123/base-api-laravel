<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;

use App\Http\Resources\Base\System\CountryCodeResource;

use Jiannei\Response\Laravel\Support\Facades\Response;

class CountryCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $json = file_get_contents(resource_path('system_data/country_code.json'));
        $data = json_decode($json, true);
        return Response::success(CountryCodeResource::collection($data));
    }
}
