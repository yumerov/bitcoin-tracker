<?php

// phpcs:disable Squiz.Objects.ObjectInstantiation.NotAssigned

namespace App\Http\Controllers;

use App\DTO\PriceNotificationDTO;
use App\Http\Requests\PriceNotificationRequest;
use App\Services\PriceNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/subscribe",
 *     summary="If the given combination email-price does not exists, persists it to notify for the price changes",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="price", type="number", minimum=0),
 *         )
 *     ),
 *     @OA\Response(
 *         response="204",
 *         description="Successfully subscribed",
 *     ),
 *     @OA\Response(
 *         response="422",
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 additionalProperties=@OA\Schema(
 *                     type="array",
 *                     @OA\Items(type="string")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class SubscribeController extends BaseController
{
    public function __construct(
        private readonly PriceNotificationService $service
    ) { }

    public function __invoke(PriceNotificationRequest $request): JsonResponse
    {
        $success = $this->service->subscribe(new PriceNotificationDTO(
            $request->get('email'),
            $request->get('price')
        ));

        if ($success === true) {
            return new JsonResponse(status: Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(data: ['message' => 'Something went wrong'], status: Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
