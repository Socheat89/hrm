<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    public function __invoke(User $user): Response
    {
        $viewer = auth()->user();

        if (! $viewer) {
            abort(401);
        }

        $canView = $viewer->id === $user->id || $viewer->hasAnyRole(['Super Admin', 'Admin / HR']);
        if (! $canView) {
            abort(403);
        }

        $path = $user->photo_path;

        if ($path && Storage::disk('public')->exists($path)) {
            $content = Storage::disk('public')->get($path);
            $mimeType = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';

            return response($content, 200, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        return $this->placeholderResponse($user->name);
    }

    private function placeholderResponse(string $name): Response
    {
        $initial = strtoupper(mb_substr(trim($name), 0, 1) ?: 'U');
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
  <rect width="80" height="80" rx="40" fill="#DBEAFE"/>
  <text x="40" y="49" text-anchor="middle" font-size="30" font-family="Arial, sans-serif" font-weight="700" fill="#1D4ED8">{$initial}</text>
</svg>
SVG;

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
