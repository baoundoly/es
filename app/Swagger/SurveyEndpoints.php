<?php

namespace App\Swagger;

/**
 * @OA\Tag(
 *   name="Surveys",
 *   description="Survey APIs"
 * )
 *
 * @OA\Post(
 *   path="/api/surveys",
 *   tags={"Surveys"},
 *   summary="Create survey",
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"voter_info_id","result_id"},
 *       @OA\Property(property="voter_info_id", type="integer", example=10),
 *       @OA\Property(property="voter_no", type="string", nullable=true),
 *       @OA\Property(property="email", type="string", format="email", nullable=true),
 *       @OA\Property(property="apartment", type="string", nullable=true),
 *       @OA\Property(property="flat_no", type="string", nullable=true),
 *       @OA\Property(property="contact", type="string", nullable=true),
 *       @OA\Property(property="result_id", type="integer", example=1),
 *       @OA\Property(property="new_address", type="string", nullable=true),
 *       @OA\Property(property="survey_time", type="string", format="time", nullable=true),
 *       @OA\Property(property="is_given_voterslip", type="integer", description="1=Yes,0=NA,2=No", example=0),
 *       @OA\Property(property="extra_info", type="string", nullable=true),
 *       @OA\Property(property="latitude", type="number", format="double", nullable=true),
 *       @OA\Property(property="longitude", type="number", format="double", nullable=true)
 *     )
 *   ),
 *   @OA\Response(
 *     response=201,
 *     description="Created",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string"),
 *       @OA\Property(property="data", ref="#/components/schemas/Survey")
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   @OA\Response(response=422, description="Validation error")
 * )
 *
 * @OA\Get(
 *   path="/api/surveys",
 *   tags={"Surveys"},
 *   summary="List surveys (paginated)",
 *   @OA\Response(
 *     response=200,
 *     description="Paginated surveys",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="data", type="object")
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated")
 * )
 *
 * @OA\Get(
 *   path="/api/surveys/{id}",
 *   tags={"Surveys"},
 *   summary="Get survey by ID",
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Survey",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="data", type="object")
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   @OA\Response(response=404, description="Not found")
 * )
 *
 * @OA\Put(
 *   path="/api/surveys/{id}",
 *   tags={"Surveys"},
 *   summary="Update survey",
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"voter_info_id","result_id"},
 *       @OA\Property(property="voter_info_id", type="integer", example=10),
 *       @OA\Property(property="voter_no", type="string", nullable=true),
 *       @OA\Property(property="email", type="string", format="email", nullable=true),
 *       @OA\Property(property="apartment", type="string", nullable=true),
 *       @OA\Property(property="flat_no", type="string", nullable=true),
 *       @OA\Property(property="contact", type="string", nullable=true),
 *       @OA\Property(property="result_id", type="integer", example=1),
 *       @OA\Property(property="new_address", type="string", nullable=true),
 *       @OA\Property(property="survey_time", type="string", format="time", nullable=true),
 *       @OA\Property(property="is_given_voterslip", type="integer", description="1=Yes,0=NA,2=No", example=0),
 *       @OA\Property(property="extra_info", type="string", nullable=true),
 *       @OA\Property(property="latitude", type="number", format="double", nullable=true),
 *       @OA\Property(property="longitude", type="number", format="double", nullable=true)
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Updated",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string"),
 *       @OA\Property(property="data", ref="#/components/schemas/Survey")
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   @OA\Response(response=404, description="Not found"),
 *   @OA\Response(response=422, description="Validation error")
 * )
 *
 * @OA\Delete(
 *   path="/api/surveys/{id}",
 *   tags={"Surveys"},
 *   summary="Delete survey",
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Deleted",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string")
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   @OA\Response(response=404, description="Not found")
 * )
 */
final class SurveyEndpoints
{
}
