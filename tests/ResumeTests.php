<?php


use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\Helpers\TestDatabaseCreationHelper;


class ResumeTests extends TestCase
{
    use DatabaseTransactions;

    private string $defaultName = 'Matheus Teste';
    private string $defaultEmail = 'tharujo097@gmail.com';
    private string $defaultPassword = 'teste@123';
    private string $fullName = 'Matheus Araujo Curriculo';
    private string $description = 'Desenvolvedor Frontend';
    private string $mail = 'matheus@teste.com';
    private string $phone = '83996222245';
    private string $site = 'www.mysite.com';
    private string $level = 'JUNIOR';
    private string $scholarship = 'HIGHSCHOOL';
    private string $techStack = 'php - js - sql';
    private int $cityId = 1;

    private $authenticatedHeader;

    private $url = '/api/resume';
    private TestDatabaseCreationHelper $testDatabaseCreationHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testDatabaseCreationHelper = new TestDatabaseCreationHelper();

        $this->testDatabaseCreationHelper->createTestUser(
            $this->defaultName,
            $this->defaultEmail,
            $this->defaultPassword,
            1
        );

        $this->login();
    }

    public function testCreateResume()
    {
        $this->post($this->url, [
            'name' => $this->fullName,
            'description' => $this->description,
            'mail' => $this->mail,
            'phone' => $this->phone,
            'site' => $this->site,
            'level' => $this->level,
            'scholarship' => $this->scholarship,
            'tech_stack' => $this->techStack,
            'city_id' => $this->cityId
            ],
            $this->authenticatedHeader
        )->response;

        self::assertResponseStatus(200);
    }

    public function testGetResumes()
    {
        $postResponse = $this->post($this->url, [
            'name' => $this->fullName,
            'description' => $this->description,
            'mail' => $this->mail,
            'phone' => $this->phone,
            'site' => $this->site,
            'level' => $this->level,
            'scholarship' => $this->scholarship,
            'tech_stack' => $this->techStack,
            'city_id' => $this->cityId
        ],
            $this->authenticatedHeader
        )->response;

        self::assertEquals(Response::HTTP_CREATED, $postResponse->getStatusCode());

        $getResponse = $this->json('GET',  $this->url, [
            'search' => $this->fullName,
            'description' => $this->description,
            'mail' => $this->mail,
            'phone' => $this->phone,
            'site' => $this->site,
            'level' => $this->level,
            'scholarship' => $this->scholarship,
            'tech_stack' => $this->techStack,
            'city_id' => $this->cityId,
            'created_at' => 'asc',
            'page' => 1,
            'per_page' => 10
        ])->response;

        self::assertEquals(Response::HTTP_OK, $getResponse->getStatusCode());
    }

    public function testUpdateResume()
    {
        $postResponse = $this->post($this->url, [
            'name' => $this->fullName,
            'description' => $this->description,
            'mail' => $this->mail,
            'phone' => $this->phone,
            'site' => $this->site,
            'level' => $this->level,
            'scholarship' => $this->scholarship,
            'tech_stack' => $this->techStack,
            'city_id' => $this->cityId
        ],
            $this->authenticatedHeader
        )->response;

        self::assertEquals(Response::HTTP_CREATED, $postResponse->getStatusCode());

        $resumeId = $postResponse->getOriginalContent()['id'];

        $getResponse = $this->json('PUT',  $this->url.'/'.$resumeId, [
            'name' => $this->fullName,
            'description' => $this->description,
            'mail' => $this->mail,
            'phone' => $this->phone,
            'site' => $this->site,
            'level' => $this->level,
            'scholarship' => $this->scholarship,
            'tech_stack' => $this->techStack,
            'city_id' => $this->cityId,
            'created_at' => 'asc',
            'page' => 1,
            'per_page' => 10
        ], $this->authenticatedHeader)->response;

        self::assertEquals(Response::HTTP_OK, $getResponse->getStatusCode());
    }

    public function testDeleteResume()
    {
        $postResponse = $this->post($this->url, [
            'name' => $this->fullName,
            'description' => $this->description,
            'mail' => $this->mail,
            'phone' => $this->phone,
            'site' => $this->site,
            'level' => $this->level,
            'scholarship' => $this->scholarship,
            'tech_stack' => $this->techStack,
            'city_id' => $this->cityId
        ],
            $this->authenticatedHeader
        )->response;

        self::assertEquals(Response::HTTP_CREATED, $postResponse->getStatusCode());

        $resumeId = $postResponse->getOriginalContent()['id'];

        $deleteResponse = $this->json('DELETE',  $this->url.'/'.$resumeId, [], $this->authenticatedHeader)->response;

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
