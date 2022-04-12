<?php


namespace App\Http\Controllers;


use App\Services\Location\LocationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LocationController extends Controller
{
    private LocationServiceInterface $locationService;

    /**
     * LocationController constructor.
     * @param LocationServiceInterface $locationService
     */
    public function __construct(LocationServiceInterface $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * @OA\Get(
     *     tags={"Localização"},
     *     path="/api/location",
     *     description="EP para buscar cidades no sistema paginado",
     *  @OA\Parameter(
     *     name="search",
     *     required=false,
     *     description="Nome da cidade para pesquisar",
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *      )
     *    ),
     *  @OA\Parameter(
     *     name="page",
     *     required=true,
     *     description="Numero da pagina",
     *     in="query",
     *     @OA\Schema(
     *         type="integer"
     *      )
     *    ),
     * @OA\Parameter(
     *     name="per_page",
     *     required=true,
     *     description="Quantidade de items por pagina",
     *     in="query",
     *     @OA\Schema(
     *         type="integer"
     *      )
     *    ),
     *   @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *    ),
     *   @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *    ),
     *    @OA\Response(
     *          response=422,
     *          description="Unprocessed Entity"
     *    ),
     *    @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *    ),
     *    @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *     )
     *)
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function searchCity(Request $request): JsonResponse
    {
        @[
            'search' => $search,
            'page' => $page,
            'per_page' => $perPage,
        ] = $this->validate($request,
            [
                'search' => 'string',
                'page' => 'required|integer',
                'per_page' => 'required|integer'
            ]
        );

        $result = $this->locationService->searchCity($search, $page, $perPage);
        return response()->json($result, Response::HTTP_OK);
    }

}
