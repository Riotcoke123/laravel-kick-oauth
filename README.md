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
        <li>Secure storage in the database</li>
        <li>Artisan commands for testing &amp; refreshing tokens</li>
    </ul>
    <h2>Installation</h2>
    <ol>
        <li>Copy the following directories and files into your Laravel project:
            <ul>
                <li><code>app/Services</code></li>
                <li><code>app/Models</code></li>
                <li><code>app/Http/Controllers</code></li>
                <li><code>config/kick.php</code></li>
                <li>Migration file in <code>database/migrations/</code></li>
            </ul>
        </li>
        <li>Add routes from <code>routes/web.php</code>.</li>
        <li>Run migrations:</li>
    </ol>
    <pre><code>php artisan migrate</code></pre>
    <li>Add the following to your <code>.env</code> file:</li>
    <pre><code>
KICK_CLIENT_ID=your_client_id
KICK_CLIENT_SECRET=your_client_secret
KICK_REDIRECT_URI=https://yourapp.com/oauth/kick/callback
    </code></pre>
    <li>Add the scheduler to <code>app/Console/Kernel.php</code> for auto-refresh.</li>
    <h2>Usage</h2>
    <ul>
        <li>Start OAuth flow: <code>/oauth/kick/redirect</code></li>
        <li>Callback handled automatically: <code>/oauth/kick/callback</code></li>
        <li>Tokens stored in <code>kick_tokens</code> table.</li>
        <li>Use <code>KickOAuthService::ensureValidToken($token)</code> before API calls to automatically refresh expired tokens.</li>
    </ul>
    <p>
        For more details, refer to the Kick official documentation: 
        <a href="https://docs.kick.com/getting-started/generating-tokens-oauth2-flow" target="_blank">Kick OAuth2 Flow</a>
    </p>
    <h2>License</h2>
    <p>
        This project is licensed under the <strong>GNU General Public License v3.0</strong> - see the 
        <a href="LICENSE" target="_blank">LICENSE</a> file for details.
    </p>
</body>
</html>
