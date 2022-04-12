<?php


namespace App\Http\Controllers;

use App\Exceptions\AlreadyVerifiedException;
use App\Exceptions\duplicateMailException;
use App\Exceptions\NotVerifiedException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\WrongCodeException;
use App\Exceptions\WrongPasswordException;
use App\Services\Auth\AuthServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    private AuthServiceInterface $authService;

    /**
     * AuthController constructor.
     * @param AuthServiceInterface $authService
     */
    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     tags={"Autorização"},
     *     path="/api/auth/register",
     *     description="EP para cadastro de novo usuário no sistema.",
     *@OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Nome do usuário",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email do usuário",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="Senha do usúario",
     *                 ),
     *                @OA\Property(
     *                     property="photo",
     *                     type="file",
     *                     description="Foto de perfil do funcionário",
     *                 ),
     *                 required={"name", "email", "password", "photo"},
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
     *      ),
     *     @OA\Response(
     *          response=409,
     *          description="Conflict"
     *      ),
     *    )
     * )
     */
    public function register(Request $request): JsonResponse
    {
        @[
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'photo' => $photo
        ] = $this->validate($request,
            [
                'name' => 'string|required',
                'email' => 'email|required',
                'password' => 'string|required',
                'photo' => 'mimes:jpeg,bmp,png,jpg|required',
            ]
        );

        try {
            DB::beginTransaction();
            $result = $this->authService->register($name, $email, $password, $photo);
            DB::commit();

            return response()->json($result, Response::HTTP_CREATED);
        } catch (DuplicateMailException $e) {
            DB::rollBack();

            return response()->json(
                ['message' => trans($e->getMessage())],
                $e->getCode()
            );
        } catch (\Exception $e) {
            return response()->json(
                ['message' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()],
                500
            );
        }
    }

    /**
     * @OA\Post(
     *     tags={"Autorização"},
     *     path="/api/auth/login",
     *     description="EP para logar no sistema",
     *@OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email do usuário",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="Senha do usúario",
     *                 ),
     *                 required={"email", "password"},
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
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
    public function login(Request $request): JsonResponse
    {
        @[
            'email' => $email,
            'password' => $password
        ] = $this->validate($request,
            [
                'email' => 'email|required',
                'password' => 'string|required'
            ]
        );

        try {
            $result = $this->authService->login($email, $password);

            return response()->json($result, Response::HTTP_OK);
        } catch (NotVerifiedException | WrongPasswordException | UserNotFoundException $e) {
            return response()->json(
                ['message' => trans($e->getMessage())],
                $e->getCode()
            );
        }
    }

    /**
     * @OA\Post(
     *     tags={"Autorização"},
     *     path="/api/auth/logout",
     *     description="EP para deslogar no sistema",
     *     security={{ "apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
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
    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        $this->authService->logout($token);

        return response()->json(['message' => 'successful logged out'], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     tags={"Autorização"},
     *     path="/api/auth/privacy/policy",
     *     description="Termos de uso e politica de privacidade em PDF",
     *      @OA\Response(
     *          response=200,
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
    public function privacyPolicy()
    {
        return $this->authService->privacyPolicy();
    }

    /**
     * @OA\Post(
     *     tags={"Autorização"},
     *     path="/api/auth/code/confirm",
     *     description="EP para confirmar codigo recebido por email",
     *     security={{ "codeAuth": {} }},
     *@OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="code",
     *                     type="integer",
     *                     description="Cádigo recebido por email",
     *                 ),
     *                 example={"code": 123456},
     *                 required={"code"},
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
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
    public function confirmCode(Request $request): JsonResponse
    {
        @[
            'code' => $code
        ] = $this->validate($request,
            [
                'code' => 'integer|required'
            ]
        );

        try {
            $authorizationHeader = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $authorizationHeader);
            $result = $this->authService->confirmCode($token, $code);

            return response()->json($result, Response::HTTP_OK);
        } catch (WrongCodeException | UserNotFoundException $e) {
            return response()->json(
                ['message' => trans($e->getMessage())],
                $e->getCode()
            );
        }
    }

    /**
     * @OA\Post(
     *     tags={"Autorização"},
     *     path="/api/auth/code/resend",
     *     description="EP para reenviar codigo de verificação por email",
     *@OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="email",
     *                     description="Email para reenviar codigo de ativação de conta",
     *                 ),
     *                 required={"email"},
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
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
    public function resendCode(Request $request): JsonResponse
    {
        @[
            'email' => $email
        ] = $this->validate($request,
            [
                'email' => 'email|required'
            ]
        );

        try {
            DB::beginTransaction();
            $result = $this->authService->resendCode($email);
            DB::commit();

            return response()->json($result, Response::HTTP_CREATED);
        } catch (AlreadyVerifiedException | UserNotFoundException $e) {
            DB::rollBack();

            return response()->json(
                ['message' => trans($e->getMessage())],
                $e->getCode()
            );
        }
    }
}
