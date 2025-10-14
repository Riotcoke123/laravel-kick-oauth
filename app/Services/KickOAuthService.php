<?php

namespace App\Services;

use App\Models\KickToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class KickOAuthService
{
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;
    private string $authorizeUrl;
    private string $tokenUrl;
    private string $revokeUrl;
    private array $scopes;

    public function __construct()
    {
        $config = config('kick');
        $this->clientId     = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
        $this->redirectUri  = $config['redirect_uri'];
        $this->authorizeUrl = $config['authorize_url'];
        $this->tokenUrl     = $config['token_url'];
        $this->revokeUrl    = $config['revoke_url'];
        $this->scopes       = $config['scopes'];
    }

    public static function generateCodeVerifier(int $length = 128): string
    {
        return rtrim(strtr(base64_encode(random_bytes($length)), '+/', '-_'), '=');
    }

    public static function generateCodeChallenge(string $verifier): string
    {
        $hash = hash('sha256', $verifier, true);
        return rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
    }

    public function getAuthorizationUrl(string $state, string $codeChallenge): string
    {
        $params = [
            'response_type'        => 'code',
            'client_id'            => $this->clientId,
            'redirect_uri'         => $this->redirectUri,
            'scope'                => implode(' ', $this->scopes),
            'state'                => $state,
            'code_challenge'       => $codeChallenge,
            'code_challenge_method'=> 'S256',
        ];

        return $this->authorizeUrl . '?' . http_build_query($params);
    }

    public function getToken(string $code, string $codeVerifier): array
    {
        $response = $this->postForm($this->tokenUrl, [
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => $this->redirectUri,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code_verifier' => $codeVerifier,
        ]);

        $this->storeTokens($response);
        return $response;
    }

    public function refreshToken(KickToken $token): ?KickToken
    {
        if (!$token->refresh_token) {
            Log::warning("Kick token for user {$token->user_id} missing refresh token");
            return null;
        }

        $response = $this->postForm($this->tokenUrl, [
            'grant_type'     => 'refresh_token',
            'refresh_token'  => $token->refresh_token,
            'client_id'      => $this->clientId,
            'client_secret'  => $this->clientSecret,
        ]);

        $token->update([
            'access_token'  => $response['access_token'],
            'refresh_token' => $response['refresh_token'] ?? $token->refresh_token,
            'expires_in'    => $response['expires_in'],
            'expires_at'    => KickToken::calculateExpiry($response['expires_in']),
        ]);

        Log::info("Kick token auto-refreshed for user {$token->user_id}");
        return $token;
    }

    public function ensureValidToken(KickToken $token): string
    {
        if ($token->isExpired()) {
            $token = $this->refreshToken($token);
        }
        return $token->access_token;
    }

    public function revokeToken(string $token): bool
    {
        Http::asForm()->post($this->revokeUrl, ['token' => $token]);
        return true;
    }

    private function postForm(string $url, array $data): array
    {
        $response = Http::asForm()->post($url, $data);

        if ($response->failed()) {
            throw new RuntimeException('Kick API error: ' . $response->body());
        }

        return $response->json();
    }

    private function storeTokens(array $data): void
    {
        $userId = auth()->id();

        KickToken::updateOrCreate(
            ['user_id' => $userId],
            [
                'access_token'  => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? null,
                'expires_in'    => $data['expires_in'],
                'expires_at'    => KickToken::calculateExpiry($data['expires_in']),
            ]
        );
    }
}
