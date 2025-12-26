<?php

namespace App\Swagger;

/**
 * @OA\Tag(
 *   name="Auth",
 *   description="Authentication"
 * )
 *
 * @OA\Post(
 *   path="/api/login",
 *   tags={"Auth"},
 *   summary="Login (admin guard)",
 *   security={},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
 *       @OA\Property(property="password", type="string", format="password", example="secret")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Authenticated",
 *     @OA\JsonContent(
 *       @OA\Property(property="access_token", type="string"),
 *       @OA\Property(property="token_type", type="string", example="Bearer"),
 *       @OA\Property(property="user", ref="#/components/schemas/Admin")
 *     )
 *   ),
 *   @OA\Response(
 *     response=401,
 *     description="Invalid credentials",
 *     @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Invalid credentials")
 *     )
 *   ),
 *   @OA\Response(
 *     response=422,
 *     description="Validation error"
 *   )
 * )
 *
 * @OA\Post(
 *   path="/api/logout",
 *   tags={"Auth"},
 *   summary="Logout (revoke current token)",
 *   @OA\Response(
 *     response=200,
 *     description="Logged out",
 *     @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Logged out")
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated")
 * )
 */
final class AuthEndpoints
{
}
