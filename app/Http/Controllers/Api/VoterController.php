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
                'include_surveys' => 'nullable|in:all,latest,none',
            ]);

            $perPage = $validated['per_page'] ?? 50;
            $wardId = $validated['ward_no_id'];

            // Get ward name
            $ward = WardNo::find($wardId);

            $include = $validated['include_surveys'] ?? 'none';

            // Prepare base query
            $query = VoterInfo::where('ward_no_id', $wardId)
                ->select('id', 'serial_no', 'name', 'voter_no', 'father_name', 'mother_name', 'profession', 'date_of_birth', 'address', 'gender', 'file_no')
                ->orderBy('serial_no', 'asc');

            if ($include === 'all') {
                $query->with('surveys');
            } elseif ($include === 'latest') {
                $query->with(['surveys' => function($q) { $q->orderBy('created_at', 'desc'); }]);
            }

            // Get voter info with pagination
            $voters = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Voter list retrieved successfully',
                'ward' => [
                    'id' => $ward->id,
                    'name' => $ward->name,
                ],
                'data' => array_map(function($voter) use ($include) {
                    if ($include === 'all') {
                        return $voter->toArray();
                    } elseif ($include === 'latest') {
                        $arr = $voter->toArray();
                        $arr['survey'] = null;
                        if (isset($voter->surveys) && count($voter->surveys) > 0) {
                            $arr['survey'] = $voter->surveys[0]->toArray();
                        }
                        unset($arr['surveys']);
                        return $arr;
                    }

                    return $voter->toArray();
                }, $voters->items()),
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
    public function getVoterById(Request $request, $id)
    {
        try {
            $include = $request->query('include_surveys', 'all');

            $query = VoterInfo::where('id', $id);
            if ($include === 'all') {
                $query->with('surveys');
            } elseif ($include === 'latest') {
                $query->with(['surveys' => function($q) { $q->orderBy('created_at', 'desc'); }]);
            }

            $voter = $query->first();

            if (!$voter) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voter not found',
                ], 404);
            }

            $data = $voter->toArray();
            if ($include === 'latest') {
                $data['survey'] = null;
                if (isset($voter->surveys) && count($voter->surveys) > 0) {
                    $data['survey'] = $voter->surveys[0]->toArray();
                }
                unset($data['surveys']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Voter retrieved successfully',
                'data' => $data,
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
                'include_surveys' => 'nullable|in:all,latest,none',
            ]);

            $include = $validated['include_surveys'] ?? 'all';

            $query = VoterInfo::where('voter_no', $validated['voter_no']);
            if ($include === 'all') {
                $query->with('surveys');
            } elseif ($include === 'latest') {
                $query->with(['surveys' => function($q) { $q->orderBy('created_at', 'desc'); }]);
            }

            $voter = $query->first();

            if (!$voter) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voter not found',
                ], 404);
            }

            if ($voter && $include === 'latest') {
                $arr = $voter->toArray();
                $arr['survey'] = null;
                if (isset($voter->surveys) && count($voter->surveys) > 0) {
                    $arr['survey'] = $voter->surveys[0]->toArray();
                }
                unset($arr['surveys']);
                $voterData = $arr;
            } else {
                $voterData = $voter ? $voter->toArray() : null;
            }

            return response()->json([
                'success' => true,
                'message' => 'Voter found',
                'data' => $voterData,
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
                'include_surveys' => 'nullable|in:all,latest,none',
            ]);

            $perPage = $validated['per_page'] ?? 500000;

            $ward = WardNo::find($validated['ward_no_id']);

            $include = $validated['include_surveys'] ?? 'all';

            $query = VoterInfo::where('ward_no_id', $validated['ward_no_id'])
                ->whereNotNull('address')
                ->where('address', '!=', '')
                ->where('address', 'like', '%' . $validated['address'] . '%')
                ->select('id', 'serial_no', 'name', 'voter_no', 'father_name', 'mother_name', 'profession', 'date_of_birth', 'address', 'gender', 'file_no')
                ->orderBy('serial_no', 'asc');

            if ($include === 'all') {
                $query->with('surveys');
            } elseif ($include === 'latest') {
                $query->with(['surveys' => function($q) { $q->orderBy('created_at', 'desc'); }]);
            }

            $voters = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Voters retrieved successfully',
                'filters' => [
                    'ward' => [ 'id' => $ward->id, 'name' => $ward->name ],
                    'address' => $validated['address'],
                ],
                'data' => array_map(function($voter) use ($include) {
                    if ($include === 'all') {
                        return $voter->toArray();
                    } elseif ($include === 'latest') {
                        $arr = $voter->toArray();
                        $arr['survey'] = null;
                        if (isset($voter->surveys) && count($voter->surveys) > 0) {
                            $arr['survey'] = $voter->surveys[0]->toArray();
                        }
                        unset($arr['surveys']);
                        return $arr;
                    }

                    return $voter->toArray();
                }, $voters->items()),
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

    /**
     * Update cant_access field for voters matching ward and address
     * POST /api/voters/update-cant-access
     * Body: { ward_no_id, address, cant_access }
     */
    public function updateCantAccess(Request $request)
    {
        try {
            $validated = $request->validate([
                'ward_no_id' => 'required|exists:ward_nos,id',
                'address' => 'required|string|min:1|max:1000',
                'cant_access' => 'nullable|in:0,1',
            ]);

            $wardId = $validated['ward_no_id'];
            $address = $validated['address'];
            $value = array_key_exists('cant_access', $validated) ? $validated['cant_access'] : null;

            $updatedCount = VoterInfo::where('ward_no_id', $wardId)
                ->whereNotNull('address')
                ->where('address', '!=', '')
                ->where('address', $address)
                ->update(['cant_access' => $value]);

            return response()->json([
                'success' => true,
                'message' => 'cant_access updated',
                'updated' => $updatedCount,
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
                'message' => 'Error updating cant_access',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update ward_no_id and address for voters matching ward and exact address
     * POST /api/voters/update-address
     * Body: { ward_no_id, address, new_ward_no_id, new_address }
     */
    public function updateAddress(Request $request)
    {
        try {
            $validated = $request->validate([
                'voter_id' => 'required|exists:voter_infos,id',
                'new_ward_no_id' => 'required|exists:ward_nos,id',
                'new_address' => 'required|string|min:1|max:1000',
                'new_voter_no' => 'nullable|string|max:255',
            ]);

            $voterId = $validated['voter_id'];
            $newWardId = $validated['new_ward_no_id'];
            $newAddress = $validated['new_address'];
            $newVoterNo = $validated['new_voter_no'] ?? null;

            $voter = VoterInfo::find($voterId);
            if (!$voter) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voter not found',
                ], 404);
            }

            if ($newVoterNo !== null) {
                $exists = VoterInfo::where('voter_no', $newVoterNo)->where('id', '!=', $voterId)->exists();
                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'new_voter_no already exists for another voter',
                    ], 422);
                }
            }

            $updateData = ['ward_no_id' => $newWardId, 'address' => $newAddress];
            if ($newVoterNo !== null) {
                $updateData['voter_no'] = $newVoterNo;
            }

            $voter->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'voter updated',
                'updated' => 1,
            ], 200);
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating address',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
