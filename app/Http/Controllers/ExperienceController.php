<?php


namespace App\Http\Controllers;



use App\Exceptions\ExperienceNotFoundException;
use App\Services\Experience\ExperienceServiceInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExperienceController extends Controller
{
    private ExperienceServiceInterface $experienceService;

    public function __construct(ExperienceServiceInterface $experienceService)
    {
        $this->experienceService = $experienceService;
    }

    public function createExperience(Request $request)
    {
        @[
            'company' => $company,
            'description' => $description,
            'start' => $start,
            'end' => $end,
            'resume_id' => $resumeId,
            'city_id' => $cityId
        ] = $this->validate($request,
            [
                'company' => 'string|required',
                'description' => 'string|required',
                'start' => 'string|required',
                'end' => 'string',
                'resume_id' => 'integer|required',
                'city_id' => 'integer|required'
            ]
        );

        $userId = $request->user->id;

        try {
            $result = $this->experienceService->createExperience(
                $userId,
                $company,
                $description,
                $start,
                $end,
                $resumeId,
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

    public function getExperiences(Request $request)
    {
        @[
            'company' => $company,
            'start' => $start,
            'end' => $end,
            'resume_id' => $resumeId,
            'city_id' => $cityId
        ] = $this->validate($request,
            [
                'company' => 'string',
                'start' => 'date|required',
                'end' => 'date',
                'resume_id' => 'integer|required',
                'city_id' => 'integer|required'
            ]
        );

        $userId = $request->user ? $request->user->id : null;

        try {
            $result = $this->experienceService->getExperiences(
                $userId,
                $company,
                $start,
                $end,
                $resumeId,
                $cityId,
            );

            return response()->json($result, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function updateExperience(Request $request, int $experienceId)
    {
        @[
            'company' => $company,
            'description' => $description,
            'start' => $start,
            'end' => $end,
            'city_id' => $cityId,
        ] = $this->validate($request,
            [
                'company' => 'string',
                'description' => 'string',
                'start' => 'date',
                'end' => 'date',
                'city_id' => 'integer'
            ]
        );

        $userId = $request->user->id;

        try {
            $result = $this->experienceService->updateExperience(
                $userId,
                $company,
                $description,
                $start,
                $end,
                $cityId,
                $experienceId
            );

            return response()->json($result, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch(ExperienceNotFoundException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                $e->getCode()
            );
        }
    }

    public function deleteExperience(Request $request, int $experienceId)
    {
        $userId = $request->user->id;

        try {
            $this->experienceService->deleteExperience($userId, $experienceId);

            return response()->json([], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch(ExperienceNotFoundException $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                $e->getCode()
            );
        }
    }
}
