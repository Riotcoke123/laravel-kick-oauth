<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel Kick OAuth2 Integration</title>
</head>
<body>
    <h1>Laravel Kick OAuth2 Integration</h1>
    <p>
        This package integrates <strong>Kick OAuth2</strong> into Laravel with support for:
    </p>
    <ul>
        <li>Authorization Code + PKCE flow</li>
        <li>App Access Token (Client Credentials)</li>
        <li>Auto-refresh of expired tokens</li>
        <li>Secure storage in database</li>
        <li>Artisan commands for testing &amp; refreshing tokens</li>
    </ul>\
    <h2>Installation</h2>
    <ol>
        <li>Copy the <code>app/Services</code>, <code>app/Models</code>, <code>app/Http/Controllers</code>, <code>config/kick.php</code> and migration into your Laravel project.</li>
        <li>Add routes from <code>routes/web.php</code>.</li>
        <li>Run migrations:</li>
    </ol>
    <pre><code>php artisan migrate</code></pre>
    <li>Add <code>.env</code> variables:</li>
    <pre><code>
KICK_CLIENT_ID=your_client_id
KICK_CLIENT_SECRET=your_client_secret
KICK_REDIRECT_URI=https://yourapp.com/oauth/kick/callback
    </code></pre>
    <li>Add scheduler to <code>app/Console/Kernel.php</code> for auto-refresh.</li>
    <h2>Usage</h2>
    <ul>
        <li>Start OAuth flow: <code>/oauth/kick/redirect</code></li>
        <li>Callback handled automatically: <code>/oauth/kick/callback</code></li>
        <li>Tokens stored in <code>kick_tokens</code> table.</li>
        <li>Use <code>KickOAuthService::ensureValidToken($token)</code> before API calls to automatically refresh expired tokens.</li>
    </ul>
    <p>For more details, refer to the Kick official documentation: <a href="https://docs.kick.com/getting-started/generating-tokens-oauth2-flow" target="_blank">Kick OAuth2 Flow</a></p>
</body>
</html>
