<?php

namespace Modules\Language\Http\Controllers\Client;

use Modules\Language\Services\LanguageSettingService;

use Modules\Language\Models\LanguageSetting;

use Modules\Language\Http\Resources\Client\LanguageSetting\LanguageSettingResource;

use Jiannei\Response\Laravel\Support\Facades\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanguageSettingController extends Controller
{
    protected $languageSettingService;

    public function __construct(LanguageSettingService $languageSettingService)
    {
        $this->languageSettingService = $languageSettingService;
    }

    /**
     * 取得語系設定
     *
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'sometimes|string|nullable',
            'page_size' => 'sometimes|integer',
            'page' => 'sometimes|integer',
            'orderby' => 'sometimes|string',
        ]);

        $languageSetting = LanguageSetting::query();

        if ($keyword = $request->input('keyword')) {
            $languageSetting->whereKeyword(['name'], $keyword);
        }

        if ($orderby = $request->input('orderby')) {
            $languageSetting->queryOrderBy($orderby);
        }

        $data = $request->filled(['page_size', 'page']) ? $this->paginate($request, $languageSetting) : $languageSetting->get();

        return Response::success(LanguageSettingResource::collection($data));
    }
}
