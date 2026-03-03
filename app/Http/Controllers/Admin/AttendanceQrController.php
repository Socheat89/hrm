<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceQrToken;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceQrController extends Controller
{
    public function index()
    {
        $branches = Branch::query()->orderBy('name')->get();
        $tokens = AttendanceQrToken::query()->with('branch')->latest()->paginate(20);

        return view('admin.qr.index', compact('branches', 'tokens'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => ['required', 'exists:branches,id'],
        ]);

        // Deactivate ANY previous active tokens for this branch so only one remains valid
        AttendanceQrToken::query()
            ->where('branch_id', $validated['branch_id'])
            ->where('is_active', true)
            ->update(['is_active' => false]);

        AttendanceQrToken::query()->create([
            'branch_id'  => $validated['branch_id'],
            'token_date' => today(),
            'token'      => (string) \Illuminate\Support\Str::uuid(),
            'expires_at' => null, // Never expires
            'is_active'  => true,
        ]);

        return back()->with('status', 'Static QR code generated successfully. It will not expire.');
    }

    public function qr(AttendanceQrToken $token)
    {
        abort_if(! $token->is_active, 404);

        return response(
            QrCode::format('svg')->size(280)->generate($token->token),
            200,
            ['Content-Type' => 'image/svg+xml']
        );
    }

    public function print(AttendanceQrToken $token)
    {
        abort_if(! $token->is_active, 404);

        return view('admin.qr.print', compact('token'));
    }
}
