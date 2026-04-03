<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Services\VoucherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PackageController extends Controller
{
    protected VoucherService $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    /**
     * Get all active packages
     */
    public function index(): AnonymousResourceCollection
    {
        $packages = Package::active()
            ->with('vouchers')
            ->orderBy('type')
            ->orderBy('value_numeric')
            ->get();

        return PackageResource::collection($packages)->additional([
            'meta' => [
                'total' => $packages->count(),
            ],
        ]);
    }

    /**
     * Get package details
     */
    public function show(int $id): JsonResponse|PackageResource
    {
        $package = Package::active()
            ->with('vouchers')
            ->find($id);

        if (!$package) {
            return response()->json([
                'message' => 'Package not found',
            ], 404);
        }

        return new PackageResource($package);
    }
}
