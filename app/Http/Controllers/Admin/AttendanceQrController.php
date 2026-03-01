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

        // Deactivate any existing tokens for this branch today
        AttendanceQrToken::query()
            ->where('branch_id', $validated['branch_id'])
            ->whereDate('token_date', today())
            ->update(['is_active' => false]);

        AttendanceQrToken::query()->create([
            'branch_id'  => $validated['branch_id'],
            'token_date' => today(),
            'token'      => (string) \Illuminate\Support\Str::uuid(),
            'expires_at' => now()->setTime(23, 59, 59),
            'is_active'  => true,
        ]);

        return back()->with('status', 'QR code generated successfully. Valid until midnight.');
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
}
