<?php


namespace App\Http\Controllers;


use App\Exceptions\ResumeNotFoundException;
use App\Services\Resume\ResumeServiceInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ResumeController extends Controller
{
    private ResumeServiceInterface $resumeService;

    public function __construct(ResumeServiceInterface $resumeService)
    {
        $this->resumeService = $resumeService;
    }

    /**
     * @OA\Get(
     *     tags={"Curriculos"},
     *     path="/api/resume",
     *     description="Listagem de currículos",
     *  @OA\Parameter(
     *     name="search",
     *     required=false,
     *     description="Nome do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="mail",
     *     required=false,
     *     description="E-mail do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *
     *      ),
     *    ),
     *     @OA\Parameter(
     *     name="phone",
     *     required=false,
     *     description="Telefone do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *
     *      ),
     *    ),
     *     @OA\Parameter(
     *     name="search",
     *     required=site,
     *     description="Website do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="level",
     *     required=false,
     *     description="Nível do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *          @OA\Items(
     *            type="string",
     *            enum={"JUNIOR", "STAFF", "SENIOR"},
     *            default="JUNIOR"
     *        ),
     *      ),
     *    ),
     *     @OA\Parameter(
     *     name="scholarship",
     *     required=false,
     *     description="Escolaridade do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *          @OA\Items(
     *            type="string",
     *            enum={"HIGHSCHOOL", "BACHELOR", "MASTER"},
     *            default="HIGHSCHOOL"
     *        ),
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="created_at",
     *     required=false,
     *     description="Tipo de ordenação a partir da data de criação da vaga",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *     @OA\Items(
     *            type="string",
     *            enum={"asc", "desc"},
     *            default="asc"
     *        ),
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="tech_stack",
     *     required=false,
     *     description="Tecnologias do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *      )
     *    ),
     *  @OA\Parameter(
     *     name="page",
     *     required=true,
     *     description="Pagina da pesquisa",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         default=1,
     *      )
     *    ),
     *  @OA\Parameter(
     *     name="per_page",
     *     required=true,
     *     description="Quantidade de itens por pagina",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         default=20,
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
     */
    /**
     * @OA\Get(
     *     tags={"Curriculos"},
     *     path="/api/resume/my",
     *     description="Listagem de curriculos de candidatos",
     *     security={{ "apiAuth": {} }},
     *  @OA\Parameter(
     *     name="search",
     *     required=false,
     *     description="Nome do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="mail",
     *     required=false,
     *     description="E-mail do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *
     *      ),
     *    ),
     *     @OA\Parameter(
     *     name="phone",
     *     required=false,
     *     description="Telefone do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *
     *      ),
     *    ),
     *     @OA\Parameter(
     *     name="search",
     *     required=site,
     *     description="Website do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="level",
     *     required=false,
     *     description="Nível do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *          @OA\Items(
     *            type="string",
     *            enum={"JUNIOR", "STAFF", "SENIOR"},
     *            default="JUNIOR"
     *        ),
     *      ),
     *    ),
     *     @OA\Parameter(
     *     name="scholarship",
     *     required=false,
     *     description="Escolaridade do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *          @OA\Items(
     *            type="string",
     *            enum={"HIGHSCHOOL", "BACHELOR", "MASTER"},
     *            default="HIGHSCHOOL"
     *        ),
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="created_at",
     *     required=false,
     *     description="Tipo de ordenação a partir da data de criação da vaga",
     *     in="query",
     *     @OA\Schema(
     *         type="array",
     *     @OA\Items(
     *            type="string",
     *            enum={"asc", "desc"},
     *            default="asc"
     *        ),
     *      ),
     *    ),
     *  @OA\Parameter(
     *     name="tech_stack",
     *     required=false,
     *     description="Tecnologias do candidato",
     *     in="query",
     *     @OA\Schema(
     *         type="string"
     *      )
     *    ),
     *  @OA\Parameter(
     *     name="page",
     *     required=true,
     *     description="Pagina da pesquisa",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         default=1,
     *      )
     *    ),
     *  @OA\Parameter(
     *     name="per_page",
     *     required=true,
     *     description="Quantidade de itens por pagina",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         default=20,
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
     */
    public function getResumes(Request $request)
    {
        @[
            'search' => $search,
            'mail' => $mail,
            'phone' => $phone,
            'site' => $site,
            'level' => $level,
            'scholarship' => $scholarship,
            'tech_stack' => $techStack,
            'city_id' => $cityId,
            'created_at' => $createdAt,
            'page' => $page,
            'per_page' => $perPage
        ] = $this->validate($request,
            [
                'search' => 'string',
                'mail' => 'string',
                'phone' => 'string',
                'site' => 'string',
                'level' => 'in:JUNIOR,STAFF,SENIOR',
                'scholarship' => 'in:HIGHSCHOOL,BACHELOR,MASTER',
                'tech_stack' => 'string',
                'city_id' => 'integer',
                'created_at' => 'in:asc,desc',
                'page' => 'required|integer',
                'per_page' => 'required|integer'
            ]
        );

        $userId = $request->user ? $request->user->id : null;

        try {
            $result = $this->resumeService->getResumes(
                $userId,
                $search,
                $mail,
                $phone,
                $site,
                $level,
                $scholarship,
                $techStack,
                $cityId,
                $createdAt,
                $page,
                $perPage
            );

            return response()->json($result, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     tags={"Vagas de emprego"},
     *     path="/api/vacancy/",
     *     description="EP para alterar senha de um usuario",
     *     security={{ "apiAuth": {} }},
     *@OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="full_name",
     *                     type="string",
     *                     description="Nome do candidato",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Descrição do candidato",
     *                 ),
     *                 @OA\Property(
     *                     property="mail",
     *                     type="string",
     *                     description="E-mail do candidato",
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string",
     *                     description="Telefone do candidato",
     *                 ),
     *                @OA\Property(
     *                     property="site",
     *                     type="string",
     *                     description="Website do candidato",
     *                 ),
     *                @OA\Property(
     *                     property="level",
     *                     type="string",
     *                     description="Nível do candidato [JUNIOR, STAFF, SENIOR]",
     *                 ),
     *                @OA\Property(
     *                     property="tech_stack",
     *                     type="string",
     *                     description="Tecnologias do candidato",
     *                 ),
     *                @OA\Property(
     *                     property="scholarship",
     *                     type="string",
     *                     description="Escolaridade do candidato [HIGHSCHOOL, BACHELOR, JUNIOR]",
     *                 ),
     *                @OA\Property(
     *                     property="city_id",
     *                     type="integer",
     *                     description="Id da cidade da empresa da vaga",
     *                 ),
     *
     *                 required={"full_name", "mail", "description", "site", "phone", "level", "city_id", "scholarship", "tech_stack"},
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation"
     *       ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessed Entity"
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *    )
     * )
     */
    public function createResume(Request $request)
    {
        @[
            'name' => $name,
            'description' => $description,
            'mail' => $mail,
            'phone' => $phone,
            'site' => $site,
            'level' => $level,
            'scholarship' => $scholarship,
            'tech_stack' => $techStack,
            'city_id' => $cityId
        ] = $this->validate($request,
            [
                'name' => 'string|required',
                'description' => 'string|required',
                'mail' => 'string|required',
                'phone' => 'string|required',
                'site' => 'string|required',
                'level' => 'in:JUNIOR,STAFF,SENIOR|required',
                'scholarship' => 'in:HIGHSCHOOL,BACHELOR,MASTER|required',
                'tech_stack' => 'string|required',
                'city_id' => 'integer|required'
            ]
        );

        $userId = $request->user->id;

        try {
            $result = $this->resumeService->createResume(
                $userId,
                $name,
                $description,
                $mail,
                $phone,
                $site,
                $level,
                $scholarship,
                $techStack,
                $cityId
            );

            return response()->json($result, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function deleteResume(Request $request, int $resumeId)
    {
        $userId = $request->user->id;

        try {
            $this->resumeService->deleteResume($resumeId, $userId);

            return response()->json([], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch(ResumeNotFoundException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                $e->getCode()
            );
        }
    }

    public function updateResume(Request $request, int $resumeId)
    {
        @[
            'name' => $name,
            'description' => $description,
            'mail' => $mail,
            'phone' => $phone,
            'site' => $site,
            'level' => $level,
            'scholarship' => $scholarship,
            'tech_stack' => $techStack,
            'city_id' => $cityId
        ] = $this->validate($request,
            [
                'name' => 'string',
                'description' => 'string',
                'mail' => 'string',
                'phone' => 'string',
                'site' => 'string',
                'level' => 'in:JUNIOR,STAFF,SENIOR',
                'scholarship' => 'in:HIGHSCHOOL,BACHELOR,MASTER',
                'tech_stack' => 'string',
                'city_id' => 'integer'
            ]
        );

        try {
            $result = $this->resumeService->updateResume(
                $resumeId,
                $name,
                $description,
                $mail,
                $phone,
                $site,
                $level,
                $scholarship,
                $techStack,
                $cityId
            );

            return response()->json($result, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch(ResumeNotFoundException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                $e->getCode()
            );
        }
    }
}
