<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoLoginController extends Controller
{
    /**
     * Akun uji yang bisa dimasuki dengan satu klik.
     *
     * @var array<string, string>
     */
    private const AKUN = [
        'admin' => 'admin@its.ac.id',
        'staf' => 'staf@its.ac.id',
        'mahasiswa' => 'mahasiswa1@student.its.ac.id',
    ];

    /**
     * Login instan untuk demo. Hanya aktif saat APP_ENV=local.
     */
    public function __invoke(Request $request, string $akun): RedirectResponse
    {
        abort_unless(app()->environment('local'), 404);

        $email = self::AKUN[$akun] ?? abort(404);

        Auth::login(User::where('email', $email)->firstOrFail());
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
