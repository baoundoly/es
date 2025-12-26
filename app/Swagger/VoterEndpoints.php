<?php

namespace App\Swagger;

/**
 * @OA\Tag(
 *   name="Voters",
 *   description="Voter and ward APIs"
 * )
 *
 * @OA\Get(
 *   path="/api/wards",
 *   tags={"Voters"},
 *   summary="Get all wards",
 *   @OA\Response(
 *     response=200,
 *     description="Ward list",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string"),
 *       @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Ward")
 *       ),
 *       @OA\Property(property="count", type="integer", example=3)
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated")
 * )
 *
 * @OA\Get(
 *   path="/api/voters",
 *   tags={"Voters"},
 *   summary="Get voters by ward",
 *   @OA\Parameter(
 *     name="ward_no_id",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="integer"),
 *     description="Ward ID"
 *   ),
 *   @OA\Parameter(
 *     name="per_page",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="integer", default=50, maximum=100)
 *   ),
 *   @OA\Parameter(
 *     name="page",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="integer", default=1)
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Voters list",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string"),
 *       @OA\Property(
 *         property="ward",
 *         type="object",
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string")
 *       ),
 *       @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/VoterInfo")
 *       ),
 *       @OA\Property(property="pagination", type="object")
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   @OA\Response(response=422, description="Validation error")
 * )
 *
 * @OA\Get(
 *   path="/api/voters/{id}",
 *   tags={"Voters"},
 *   summary="Get single voter by ID",
 *   @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     @OA\Schema(type="integer")
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Voter",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string"),
 *       @OA\Property(property="data", ref="#/components/schemas/VoterInfo")
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   @OA\Response(response=404, description="Not found")
 * )
 *
 * @OA\Get(
 *   path="/api/voters/search/by-number",
 *   tags={"Voters"},
 *   summary="Search voter by voter number",
 *   @OA\Parameter(
 *     name="voter_no",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string"),
 *     description="Voter number"
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Voter",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string"),
 *       @OA\Property(property="data", ref="#/components/schemas/VoterInfo")
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   @OA\Response(response=404, description="Not found"),
 *   @OA\Response(response=422, description="Validation error")
 * )
 *
 * @OA\Get(
 *   path="/api/addresses",
 *   tags={"Voters"},
 *   summary="Address suggestions by ward (optional prefix)",
 *   description="Returns distinct addresses for a required ward. If prefix is provided, results start with that prefix.",
 *   @OA\Parameter(
 *     name="ward_no_id",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="integer"),
 *     description="Ward ID (required)"
 *   ),
 *   @OA\Parameter(
 *     name="prefix",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string"),
 *     description="Optional address prefix (starts-with)"
 *   ),
 *   @OA\Parameter(
 *     name="limit",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="integer", default=20, maximum=50),
 *     description="Max number of suggestions"
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Address suggestions",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string", example="Address suggestions retrieved successfully"),
 *       @OA\Property(property="ward_no_id", type="integer", example=3),
 *       @OA\Property(property="prefix", type="string", example="Road 12"),
 *       @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(type="string"),
 *         example={"House 3, Road 12, Dhanmondi","Road 12, Dhanmondi"}
 *       ),
 *       @OA\Property(property="count", type="integer", example=2)
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   @OA\Response(response=422, description="Validation error")
 * )
 *
 * @OA\Get(
 *   path="/api/voters/search/by-address",
 *   tags={"Voters"},
 *   summary="Get voters by ward and address (contains match)",
 *   @OA\Parameter(
 *     name="ward_no_id",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="integer"),
 *     description="Ward ID"
 *   ),
 *   @OA\Parameter(
 *     name="address",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string"),
 *     description="Address text (partial match)"
 *   ),
 *   @OA\Parameter(
 *     name="per_page",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="integer", default=50, maximum=100)
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Voters list",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string"),
 *       @OA\Property(
 *         property="filters",
 *         type="object",
 *         @OA\Property(property="ward", ref="#/components/schemas/Ward"),
 *         @OA\Property(property="address", type="string", example="Road 12")
 *       ),
 *       @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/VoterInfo")
 *       ),
 *       @OA\Property(property="pagination", type="object")
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   @OA\Response(response=422, description="Validation error")
 * )
 */
final class VoterEndpoints
{
}
