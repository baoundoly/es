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
 *   @OA\Parameter(
 *     name="include_surveys",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string", enum={"all","latest","none"}),
 *     description="Include associated surveys: all | latest | none (default: none)"
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
 *   @OA\Parameter(
 *     name="include_surveys",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string", enum={"all","latest","none"}),
 *     description="Include associated surveys: all | latest | none (default: none)"
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
 *   @OA\Parameter(
 *     name="include_surveys",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string", enum={"all","latest","none"}),
 *     description="Include associated surveys: all | latest | none (default: none)"
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
 *   @OA\Parameter(
 *     name="include_surveys",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string", enum={"all","latest","none"}),
 *     description="Include associated surveys: all | latest | none (default: none)"
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

 * @OA\Post(
 *   path="/api/voters/update-cant-access",
 *   tags={"Voters"},
 *   summary="Update cant_access flag for voters matching ward and exact address",
 *   description="Updates the `cant_access` field for all voters that match the provided `ward_no_id` and exact `address`. Authentication required.",
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         type="object",
 *         @OA\Property(property="ward_no_id", type="integer", example=3),
 *         @OA\Property(property="address", type="string", example="House 3, Road 12, Dhanmondi"),
 *         @OA\Property(property="cant_access", type="integer", nullable=true, description="null=unknown,0=not access,1=can access", example=0)
 *       )
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Update result",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string", example="cant_access updated"),
 *       @OA\Property(property="updated", type="integer", example=5)
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   @OA\Response(response=422, description="Validation error"),
 *   @OA\Response(response=500, description="Server error")
 * )
 *
 * @OA\Post(
 *   path="/api/voters/update-address",
 *   tags={"Voters"},
 *   summary="Update ward and address for voters matching ward and exact address",
 *   description="Finds voters by `ward_no_id` and exact `address` and updates their `ward_no_id` and `address` to new values.",
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/json",
 *       @OA\Schema(
 *         type="object",
 *         @OA\Property(property="voter_id", type="integer", example=10),
 *         @OA\Property(property="new_ward_no_id", type="integer", example=4),
 *         @OA\Property(property="new_address", type="string", example="House 3, Road 14, Dhanmondi"),
 *         @OA\Property(property="new_voter_no", type="string", nullable=true, example="V987654")
 *       )
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Update result",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string", example="address and ward updated"),
 *       @OA\Property(property="updated", type="integer", example=5)
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   @OA\Response(response=422, description="Validation error"),
 *   @OA\Response(response=500, description="Server error")
 * )
 */
final class VoterEndpoints
{
}
