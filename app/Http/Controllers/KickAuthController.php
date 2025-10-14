<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\KickOAuthService;
use Illuminate\Support\Facades\Session;

class KickAuthController extends Controller
{
    public function redirect(KickOAuthService $kick)
    {
        $state = bin2hex(random_bytes(16));
        $verifier = KickOAuthService::generateCodeVerifier();
        $challenge = KickOAuthService::generateCodeChallenge($verifier);

        Session::put('kick_state', $state);
        Session::put('kick_verifier', $verifier);

        return redirect($kick->getAuthorizationUrl($state, $challenge));
    }

    public function callback(Request $request, KickOAuthService $kick)
    {
        $state = Session::pull('kick_state');
        $verifier = Session::pull('kick_verifier');

        if ($request->input('state') !== $state) {
            return response('Invalid state', 400);
        }

        $code = $request->input('code');

        try {
            $tokens = $kick->getToken($code, $verifier);
            return response()->json([
                'message' => 'Kick OAuth success!',
                'tokens' => $tokens
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get token',
                'details' => $e->getMessage(),
            ], 400);
        }
    }
}
