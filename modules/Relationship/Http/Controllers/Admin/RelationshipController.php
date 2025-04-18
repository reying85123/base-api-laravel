<?php

namespace Modules\Relationship\Http\Controllers\Admin;

use Modules\Relationship\Models\Relationship;

use Modules\Relationship\Services\Relationship\RelationshipService;

use Modules\Relationship\Http\Requests\Admin\Relationship\CreateRelationshipRequest;

use Modules\Relationship\Http\Resources\Admin\Relationship\RelationshipDetailResource;
use Modules\Relationship\Http\Resources\Admin\Relationship\RelationshipResource;

use App\Http\Controllers\Controller;

use Jiannei\Response\Laravel\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelationshipController extends Controller
{
    protected $relationshipService;

    public function __construct(
        RelationshipService $relationshipService
    ) {
        $this->relationshipService = $relationshipService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate(
            $request,
            [
                'keyword' => 'sometimes',
                'page_size' => 'sometimes|integer',
                'page' => 'sometimes|integer',
                'orderby' => 'sometimes|string',
            ],
            [],
            []
        );

        $relationship = $this->relationshipService->filterQuery($request);

        $data = $request->filled(['page_size', 'page']) ? $this->paginate($request, $relationship) : $relationship->get();

        return Response::success(RelationshipResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateRelationshipRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRelationshipRequest $request)
    {
        DB::beginTransaction();

        try {
            if ($entity = $request->input('entity')) {
                $this->relationshipService->associateEntity($entity);
            }
            if ($relatedEntity = $request->input('related_entity')) {
                $this->relationshipService->associateRelatedEntity($relatedEntity);
            }
            $this->relationshipService
                ->fill($request->validated())
                ->save();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return Response::success(new RelationshipDetailResource($this->relationshipService->getModel()->refresh()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $relationship = Relationship::query()->findOrFail($id);

        $this->relationshipService->setModel($relationship);

        DB::beginTransaction();

        try {
            $this->relationshipService->delete();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::noContent();
    }
}
