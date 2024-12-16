<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Magic HRMS API's",
 *    version="1.0.0",
 *    description="API documentation for Magic HRMS",
 *    @OA\License(
 *        name="Apache 2.0",
 *        url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *    )
 * ),
 *  
 *
 * @OA\SecurityScheme(
 *     securityScheme="jwt",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * 
 * @OA\Server(
 *        description="Local Server",
 *        url="http://127.0.0.1:8000"
 *    ),
 *    @OA\Server(
 *        description="Development Server",
 *        url="https://api-magichrms.magicmindtechnologies.com"
 *    ),
 *    @OA\Server(
 *        description="Staging Server",
 *        url="https://qa-api-magichrms.magicmindtechnologies.com"
 *    ),
 */
class Controller extends BaseController
{
    //
}
