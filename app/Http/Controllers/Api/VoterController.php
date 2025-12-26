<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WardNo;
use App\Models\VoterInfo;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    /**
     * Get all ward numbers with their IDs
     * GET /api/wards
     */
    public function getAllWards()
    {
        try {
            $wards = WardNo::where('status', 1)
                ->orderBy('order', 'asc')
                ->select('id', 'name')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Ward list retrieved successfully',
                'data' => $wards,
                'count' => $wards->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving wards',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all voter info by ward number
     * GET /api/voters?ward_no_id={id}
     * Parameters:
     *  - ward_no_id (required): Ward ID
     *  - page (optional): Page number for pagination (default: 1)
     *  - per_page (optional): Records per page (default: 50, max: 100)
     */
    public function getVotersByWard(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'ward_no_id' => 'required|exists:ward_nos,id',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            $perPage = $validated['per_page'] ?? 50;
            $wardId = $validated['ward_no_id'];

            // Get ward name
            $ward = WardNo::find($wardId);

            // Get voter info with pagination
            $voters = VoterInfo::where('ward_no_id', $wardId)
                ->select('id', 'serial_no', 'name', 'voter_no', 'father_name', 'mother_name', 'profession', 'date_of_birth', 'address', 'gender', 'file_no')
                ->orderBy('serial_no', 'asc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Voter list retrieved successfully',
                'ward' => [
                    'id' => $ward->id,
                    'name' => $ward->name,
                ],
                'data' => $voters->items(),
                'pagination' => [
                    'total' => $voters->total(),
                    'count' => $voters->count(),
                    'per_page' => $voters->perPage(),
                    'current_page' => $voters->currentPage(),
                    'total_pages' => $voters->lastPage(),
                    'next_page_url' => $voters->nextPageUrl(),
                    'prev_page_url' => $voters->previousPageUrl(),
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving voters',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single voter by ID
     * GET /api/voters/{id}
     */
    public function getVoterById($id)
    {
        try {
            $voter = VoterInfo::find($id);

            if (!$voter) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voter not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Voter retrieved successfully',
                'data' => $voter,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving voter',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Search voter by voter_no
     * GET /api/voters/search?voter_no={voter_no}
     */
    public function searchVoterByNo(Request $request)
    {
        try {
            $validated = $request->validate([
                'voter_no' => 'required|string|min:3',
            ]);

            $voter = VoterInfo::where('voter_no', $validated['voter_no'])->first();

            if (!$voter) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voter not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Voter found',
                'data' => $voter,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching voter',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get voters by ward and address (partial match)
     * GET /api/voters/search/by-address?ward_no_id={id}&address={text}&per_page={n?}
     */
    public function getVotersByWardAndAddress(Request $request)
    {
        try {
            $validated = $request->validate([
                'ward_no_id' => 'required|exists:ward_nos,id',
                'address' => 'required|string|min:1|max:255',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            $perPage = $validated['per_page'] ?? 500000;

            $ward = WardNo::find($validated['ward_no_id']);

            $voters = VoterInfo::where('ward_no_id', $validated['ward_no_id'])
                ->whereNotNull('address')
                ->where('address', '!=', '')
                ->where('address', 'like', '%' . $validated['address'] . '%')
                ->select('id', 'serial_no', 'name', 'voter_no', 'father_name', 'mother_name', 'profession', 'date_of_birth', 'address', 'gender', 'file_no')
                ->orderBy('serial_no', 'asc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Voters retrieved successfully',
                'filters' => [
                    'ward' => [ 'id' => $ward->id, 'name' => $ward->name ],
                    'address' => $validated['address'],
                ],
                'data' => $voters->items(),
                'pagination' => [
                    'total' => $voters->total(),
                    'count' => $voters->count(),
                    'per_page' => $voters->perPage(),
                    'current_page' => $voters->currentPage(),
                    'total_pages' => $voters->lastPage(),
                    'next_page_url' => $voters->nextPageUrl(),
                    'prev_page_url' => $voters->previousPageUrl(),
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving voters by address',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get address suggestions by ward with optional prefix
     * GET /api/addresses?ward_no_id={id}&prefix={text?}&limit={n?}
     *
     * Requires ward number; if prefix provided, returns addresses starting with it.
     */
    public function getAddressSuggestions(Request $request)
    {
        try {
            $validated = $request->validate([
                'ward_no_id' => 'required|exists:ward_nos,id',
                'prefix' => 'nullable|string|min:1|max:200',
                'limit' => 'nullable|integer|min:1|max:50',
            ]);

            $limit = $validated['limit'] ?? 200000;
            $prefix = isset($validated['prefix']) ? trim($validated['prefix']) : null;

            $addressesQuery = VoterInfo::query()
                ->where('ward_no_id', $validated['ward_no_id'])
                ->whereNotNull('address')
                ->where('address', '!=', '')
                ->select('address')
                ->distinct();

            if ($prefix !== null && $prefix !== '') {
                $addressesQuery->where('address', 'like', $prefix . '%');
            }

            $addresses = $addressesQuery
                ->orderBy('address')
                ->limit($limit)
                ->pluck('address');

            return response()->json([
                'success' => true,
                'message' => 'Address suggestions retrieved successfully',
                'ward_no_id' => (int) $validated['ward_no_id'],
                'prefix' => $prefix,
                'data' => $addresses,
                'count' => $addresses->count(),
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving address suggestions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
