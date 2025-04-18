<?php

namespace Modules\PlatformAttribute\Services;

use Modules\PlatformAttribute\Models\PlatformAttribute;

use App\Enums\PlatformAttributeEnum;

use App\Services\Traits\HasModel;

use Illuminate\Support\Facades\DB;

class PlatformAttributeService
{
    use HasModel;

    /**
     * @var PlatformAttribute
     */
    protected $model;

    public function __construct(PlatformAttribute $model)
    {
        $this->model = $model;
    }

    public function update(array $data)
    {
        $values = collect($data)->map(function ($value, $name) {
            $value = $value ?: PlatformAttributeEnum::getDefaultValue($name);
            return ['name' => $name, 'value' => PlatformAttribute::parseValuetoString($value)];
        });

        return $this->model->upsert($values->toArray(), ['name'], ['value']);
    }

    /**
     * 依參數名稱取得平台參數
     *
     * @param string|array $name
     * @return string|array|null
     */
    public static function getValueByName($name)
    {
        $names = $name;

        if (!is_array($name)) {
            $names = [$name];
        }

        $platformAttribute = PlatformAttribute::whereIn('name', $names)->get();

        if (!is_array($name)) {
            $firstPlatformAttribute = $platformAttribute->first();
            $result = !!$firstPlatformAttribute ? $firstPlatformAttribute->value : null;
        } else {
            $result = collect($names)
                ->map(function ($name) use ($platformAttribute) {
                    $firstPlatformAttribute = $platformAttribute->where('name', $name)->first();
                    return !!$firstPlatformAttribute ? $firstPlatformAttribute->value : null;
                })->toArray();
        }

        return $result;
    }

    /**
     * 初始化或新增平台參數
     *
     * @param boolean $initialValue 是否初始化所有參數數值
     * @return boolean
     */
    public static function initialData($initialValue = false): bool
    {
        DB::beginTransaction();

        try {
            PlatformAttribute::upsert(
                collect(PlatformAttributeEnum::getValues())->map(function ($platformAttributeName) use ($initialValue) {
                    if ($initialValue) {
                        $value = PlatformAttributeEnum::getDefaultValue($platformAttributeName);
                    } else {
                        $nowPlatformAttribute = PlatformAttribute::where('name', $platformAttributeName)->first();

                        if (!!$nowPlatformAttribute) {
                            $value = $nowPlatformAttribute->value;
                        } else {
                            $value = PlatformAttributeEnum::getDefaultValue($platformAttributeName);
                        }
                    }

                    return ['name' => $platformAttributeName, 'value' => $value, 'description' => PlatformAttributeEnum::getPlatformAttrDescription($platformAttributeName)];
                })->toArray(),
                ['name'],
                ['value', 'description']
            );

            PlatformAttribute::whereNotIn('name', PlatformAttributeEnum::getValues())->delete();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return true;
    }
}
