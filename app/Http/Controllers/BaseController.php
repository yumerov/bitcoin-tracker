<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Bitcoin price tracker",
 *      description="An application that tracts the price and notifies the users",
 *      @OA\Contact(
 *          email="levent@yumerov.com"
 *      ),
 *      @OA\License(
 *          name="MIT License",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 */
class BaseController extends Controller
{

}
