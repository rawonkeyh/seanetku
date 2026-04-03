<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Package;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::with('package');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by package
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        // Search by username
        if ($request->filled('search')) {
            $query->where('username', 'like', '%' . $request->search . '%');
        }

        $vouchers = $query->latest()->paginate(50);
        $packages = Package::all();

        $stats = [
            'available' => Voucher::where('status', 'available')->count(),
            'reserved' => Voucher::where('status', 'reserved')->count(),
            'sold' => Voucher::where('status', 'sold')->count(),
        ];

        return view('admin.vouchers.index', compact('vouchers', 'packages', 'stats'));
    }

    public function create()
    {
        $packages = Package::all();
        return view('admin.vouchers.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'username' => 'required|string|unique:vouchers,username',
            'password' => 'required|string|min:6',
        ]);

        $validated['status'] = 'available';
        
        Voucher::create($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil ditambahkan!');
    }

    public function edit(Voucher $voucher)
    {
        $packages = Package::all();
        return view('admin.vouchers.edit', compact('voucher', 'packages'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'username' => 'required|string|unique:vouchers,username,' . $voucher->id,
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:available,reserved,sold',
        ]);

        // Only update password if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $voucher->update($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil diperbarui!');
    }

    public function destroy(Voucher $voucher)
    {
        // Prevent deletion of sold or reserved vouchers
        if (in_array($voucher->status, ['reserved', 'sold'])) {
            return back()->with('error', 'Tidak dapat menghapus voucher yang sudah reserved atau sold!');
        }

        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil dihapus!');
    }

    public function bulkCreate()
    {
        $packages = Package::all();
        return view('admin.vouchers.bulk-create', compact('packages'));
    }

    public function bulkStore(Request $request)
    {
        $mode = $request->input('generation_type', 'manual');

        $baseRules = [
            'package_id' => 'required|exists:packages,id',
            'generation_type' => 'required|in:manual,csv',
        ];

        if ($mode === 'csv') {
            $validated = $request->validate($baseRules + [
                'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            ]);

            $package = Package::findOrFail($validated['package_id']);
            $file = $request->file('csv_file');
            $handle = fopen($file->getRealPath(), 'r');

            if ($handle === false) {
                return back()->with('error', 'Gagal membaca file CSV.');
            }

            $created = 0;
            $skipped = 0;
            $row = 0;
            $seenUsernames = [];

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $row++;

                // Skip empty lines
                if (count($data) === 0 || (count($data) === 1 && trim((string) $data[0]) === '')) {
                    continue;
                }

                $username = trim((string) ($data[0] ?? ''));
                $password = trim((string) ($data[1] ?? ''));

                // Skip optional header row: username,password
                if ($row === 1 && strtolower($username) === 'username' && strtolower($password) === 'password') {
                    continue;
                }

                // Basic validation per row
                if ($username === '' || $password === '' || strlen($password) < 6 || strlen($username) > 255) {
                    $skipped++;
                    continue;
                }

                if (isset($seenUsernames[strtolower($username)]) || Voucher::where('username', $username)->exists()) {
                    $skipped++;
                    continue;
                }

                Voucher::create([
                    'package_id' => $package->id,
                    'username' => $username,
                    'password' => $password,
                    'status' => 'available',
                ]);

                $seenUsernames[strtolower($username)] = true;
                $created++;
            }

            fclose($handle);

            return redirect()->route('admin.vouchers.index')
                ->with('success', "Import CSV selesai: {$created} voucher dibuat, {$skipped} baris dilewati.");
        }

        $validated = $request->validate($baseRules + [
            'quantity' => 'required|integer|min:1|max:100',
            'prefix' => 'required|string|max:10',
        ]);

        $package = Package::findOrFail($validated['package_id']);
        $created = 0;

        for ($i = 1; $i <= $validated['quantity']; $i++) {
            $username = $validated['prefix'] . str_pad($i, 4, '0', STR_PAD_LEFT);
            $password = bin2hex(random_bytes(4));

            if (Voucher::where('username', $username)->exists()) {
                continue;
            }

            Voucher::create([
                'package_id' => $package->id,
                'username' => $username,
                'password' => $password,
                'status' => 'available',
            ]);

            $created++;
        }

        return redirect()->route('admin.vouchers.index')
            ->with('success', "Berhasil menambahkan {$created} voucher!");
    }
}
