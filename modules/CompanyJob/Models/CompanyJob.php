<?php

namespace Modules\CompanyJob\Models;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyJob extends Model
{
    use HasFactory, SoftDeletes, KeywordTrait, QueryOrderBy;

    protected $fillable = [
        'name',
        'sequence',
    ];

    public function parentJob(){
        return $this->belongsTo(CompanyJob::class, 'parent_id', 'id');
    }

    /**
     * 檢查父類別是否不在子類別內
     *
     * @param integer $parentId
     */
    public function checkParentId(int $parentId){
        if($this->id === null){
            return true;
        }

        $allParentId = [$parentId];

        do {
            $lastCompanyJob = CompanyJob::where('id', $parentId)->first();

            $parentId = $lastCompanyJob->parent_id;

            if($parentId !== null){
                array_push($allParentId, $parentId);
            }
        } while ($parentId !== null);

        return !in_array($this->id, $allParentId);
    }
}
