<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandlesCoverImage
{
    /**
     * Simpan file cover_image dari request (jika ada) dan kembalikan path-nya.
     */
    protected function storeCover(Request $request, string $directory): ?string
    {
        if ($request->hasFile('cover_image')) {
            return $request->file('cover_image')->store($directory, 'public');
        }

        return null;
    }

    /**
     * Hapus file lama dari disk agar tidak menumpuk sebagai file yatim.
     */
    protected function deleteCover(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
