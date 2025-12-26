<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *   schema="Admin",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="name", type="string", example="Admin"),
 *   @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
 *   @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *   @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *   schema="Result",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="name", type="string", example="Support"),
 *   @OA\Property(property="name_en", type="string", nullable=true, example="Support (EN)"),
 *   @OA\Property(property="order", type="integer", nullable=true),
 *   @OA\Property(property="status", type="integer", nullable=true),
 *   @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *   @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *   schema="Ward",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="name", type="string", example="Ward 1")
 * )
 *
 * @OA\Schema(
 *   schema="VoterInfo",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example=10),
 *   @OA\Property(property="serial_no", type="string", nullable=true, example="001"),
 *   @OA\Property(property="name", type="string", nullable=true, example="John Doe"),
 *   @OA\Property(property="voter_no", type="string", nullable=true, example="V123456"),
 *   @OA\Property(property="father_name", type="string", nullable=true),
 *   @OA\Property(property="mother_name", type="string", nullable=true),
 *   @OA\Property(property="profession", type="string", nullable=true),
 *   @OA\Property(property="date_of_birth", type="string", nullable=true),
 *   @OA\Property(property="address", type="string", nullable=true),
 *   @OA\Property(property="gender", type="string", nullable=true, example="Male"),
 *   @OA\Property(property="file_no", type="string", nullable=true),
 *   @OA\Property(property="ward_no_id", type="integer", nullable=true)
 * )
 *
 * @OA\Schema(
 *   schema="Survey",
 *   type="object",
 *   @OA\Property(property="id", type="integer", example=100),
 *   @OA\Property(property="voter_info_id", type="integer", example=10),
 *   @OA\Property(property="voter_no", type="string", nullable=true),
 *   @OA\Property(property="apartment", type="string", nullable=true),
 *   @OA\Property(property="flat_no", type="string", nullable=true),
 *   @OA\Property(property="contact", type="string", nullable=true),
 *   @OA\Property(property="result_id", type="integer", example=1),
 *   @OA\Property(property="new_address", type="string", nullable=true),
 *   @OA\Property(property="latitude", type="number", format="double", nullable=true),
 *   @OA\Property(property="longitude", type="number", format="double", nullable=true),
 *   @OA\Property(property="survey_time", type="string", format="time", nullable=true),
 *   @OA\Property(property="is_given_voterslip", type="integer", description="1=Yes,0=NA,2=No", example=0),
 *   @OA\Property(property="extra_info", type="string", nullable=true),
 *   @OA\Property(property="created_by", type="integer", nullable=true),
 *   @OA\Property(property="updated_by", type="integer", nullable=true),
 *   @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *   @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 * )
 */
final class Schemas
{
}
