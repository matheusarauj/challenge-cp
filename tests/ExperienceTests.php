<?php


namespace Tests;


use App\Models\Resume;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\Helpers\TestDatabaseCreationHelper;

class ExperienceTests extends \TestCase
{
    use DatabaseTransactions;

    private string $defaultName = 'Matheus Teste';
    private string $defaultEmail = 'tharujo097@gmail.com';
    private string $defaultPassword = 'teste@123';
    private string $company = 'My Company';
    private string $description = 'Trabalhei com front-end';
    private string $start = '2022-01-01';
    private string $end = '2022-02-02';
    private int $cityId = 1;
    private Resume $resume;

    private $authenticatedHeader;

    private $url = '/api/experience';
    private TestDatabaseCreationHelper $testDatabaseCreationHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testDatabaseCreationHelper = new TestDatabaseCreationHelper();

        $user = $this->testDatabaseCreationHelper->createTestUser(
            $this->defaultName,
            $this->defaultEmail,
            $this->defaultPassword,
            1
        );

        $this->resume = $this->testDatabaseCreationHelper->createResume($user->id);

        $this->login();
    }

    public function testCreateExperience()
    {
        $postResponse = $this->post($this->url, [
            'company' => $this->company,
            'description' => $this->description,
            'start' => $this->start,
            'end' => $this->end,
            'resume_id' => $this->resume->id,
            'city_id' => $this->cityId
        ],
            $this->authenticatedHeader
        )->response;

        self::assertEquals(Response::HTTP_CREATED, $postResponse->getStatusCode());
    }

    public function testGetResumes()
    {
        $postResponse = $this->post($this->url, [
            'company' => $this->company,
            'description' => $this->description,
            'start' => $this->start,
            'end' => $this->end,
            'resume_id' => $this->resume->id,
            'city_id' => $this->cityId
        ],
            $this->authenticatedHeader
        )->response;

        self::assertEquals(Response::HTTP_CREATED, $postResponse->getStatusCode());

        $getResponse = $this->json('GET',  $this->url, [
            'company' => $this->company,
            'start' => $this->start,
            'end' => $this->end,
            'resume_id' => $this->resume->id,
            'city_id' => $this->cityId
        ])->response;

        self::assertEquals(Response::HTTP_OK, $getResponse->getStatusCode());
    }

    public function testUpdateResume()
    {
        $postResponse = $this->post($this->url, [
            'company' => $this->company,
            'description' => $this->description,
            'start' => $this->start,
            'end' => $this->end,
            'resume_id' => $this->resume->id,
            'city_id' => $this->cityId
        ],
            $this->authenticatedHeader
        )->response;

        self::assertEquals(Response::HTTP_CREATED, $postResponse->getStatusCode());

        $experienceId = $postResponse->getOriginalContent()['id'];

        $updateResponse = $this->json('PUT',  $this->url.'/'.$experienceId, [
            'company' => $this->company,
            'description' => $this->description,
            'start' => $this->start,
            'end' => $this->end,
            'resume_id' => $this->resume->id,
            'city_id' => $this->cityId
        ], $this->authenticatedHeader)->response;

        self::assertEquals(Response::HTTP_OK, $updateResponse->getStatusCode());
    }

    public function testDeleteResume()
    {
        $postResponse = $this->post($this->url, [
            'company' => $this->company,
            'description' => $this->description,
            'start' => $this->start,
            'end' => $this->end,
            'resume_id' => $this->resume->id,
            'city_id' => $this->cityId
        ],
            $this->authenticatedHeader
        )->response;

        self::assertEquals(Response::HTTP_CREATED, $postResponse->getStatusCode());

        $experienceId = $postResponse->getOriginalContent()['id'];

        $deleteResponse = $this->json('DELETE',  $this->url.'/'.$experienceId, [], $this->authenticatedHeader)->response;

        self::assertEquals(Response::HTTP_OK, $deleteResponse->getStatusCode());
    }

    private function login()
    {
        $this->post(
            '/api/auth/login',
            [
                'email' => $this->defaultEmail,
                'password' => $this->defaultPassword
            ]
        );

        $token = $this->response->decodeResponseJson()['token'];

        $this->authenticatedHeader = [
            'Authorization' => "Bearer $token"
        ];
    }
}
