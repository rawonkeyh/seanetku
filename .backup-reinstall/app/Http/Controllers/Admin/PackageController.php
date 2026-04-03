<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::withCount([
            'vouchers as available_stock' => function ($query) {
                $query->where('status', 'available');
            }
        ])->get();
        
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:time,quota',
            'value' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Package::create($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil ditambahkan!');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:time,quota',
            'value' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil diperbarui!');
    }

    public function destroy(Package $package)
    {
        // Check if package has vouchers
        if ($package->vouchers()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus paket yang memiliki voucher!');
        }

        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket berhasil dihapus!');
    }
}
