<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'api/experience'], function () use ($router) {
    $router->get('/', 'ExperienceController@getExperiences');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/', 'ExperienceController@createExperience');
        $router->get('/my', 'ExperienceController@getExperiences');
        $router->put('/{experienceId}', 'ExperienceController@updateExperience');
        $router->delete('/{experienceId}', 'ExperienceController@deleteExperience');
    }
    );
}
);
