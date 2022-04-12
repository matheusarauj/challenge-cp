<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'api/resume'], function () use ($router) {
    $router->get('/', 'ResumeController@getResumes');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/', 'ResumeController@createResume');
        $router->get('/my', 'ResumeController@getResumes');
        $router->put('/{resumeId}', 'ResumeController@updateResume');
        $router->delete('/{resumeId}', 'ResumeController@deleteResume');
    }
    );
}
);
