<?php

namespace Modules\PlatformAttribute\Http\Controllers\Admin;

use Modules\PlatformAttribute\Models\PlatformAttribute;

use Modules\PlatformAttribute\Services\PlatformAttributeService;

use Modules\PlatformAttribute\Http\Resources\Admin\PlatformAttributeCollection;

use App\Enums\PlatformAttributeEnum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Jiannei\Response\Laravel\Support\Facades\Response;

class PlatformAttributeController extends Controller
{
    public function index(Request $request)
    {
        $platformAttribute = PlatformAttribute::query();

        if ($key = $request->input('key')) {
            $platformAttribute->whereIn('name', array_map('trim', explode(',', $key)));
        }

        return Response::success((new PlatformAttributeCollection($platformAttribute->get()))->toArray($request));
    }

    public function update(Request $request)
    {
        $platformAttributeKeys = PlatformAttributeEnum::getValues();

        $platformAttributeData = collect($request->all())->filter(function ($value, $key) use ($platformAttributeKeys) {
            return in_array($key, $platformAttributeKeys);
        });

        $platformAttributeService = app(PlatformAttributeService::class);

        $platformAttributeService->update($platformAttributeData->toArray());

        return Response::success(PlatformAttribute::get());
    }
}
