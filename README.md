# Worksection Provider for OAuth 2.0 Client

This package provides Worksection OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

```
composer require worksection/oauth2-worksection
```

## Usage

```php
$worksectionProvider = new \Worksection\OAuth2\Client\Provider\Worksection([
    'clientId'                => 'yourId',       // The client ID assigned to you by Worksection
    'clientSecret'            => 'yourSecret',   // The client secret assigned to you by the provider
    'redirectUri'             => 'https://redirecturl.com/query' // Redirect URI is in the worksection's application
]);

// Get authorization code
if (!isset($_GET['code'])) {
    // Get authorization URL
    $authorizationUrl = $worksectionProvider->getAuthorizationUrl();

    // Get state and store it to the session
    $_SESSION['oauth2state'] = $worksectionProvider->getState();

    // Redirect user to authorization URL
    header('Location: ' . $authorizationUrl);
    exit;
// Check for errors
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }
    exit('Invalid state');
} else {
    // Get access token
    try {
        $accessToken = $worksectionProvider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        exit($e->getMessage());
    }

    // Get resource owner
    try {
        $resourceOwner = $worksectionProvider->getResourceOwner($accessToken);
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        exit($e->getMessage());
    }
        
    // Now you can store the results to session ...
    $_SESSION['accessToken'] = $accessToken;
    $_SESSION['resourceOwner'] = $resourceOwner;
        
    // ... or do some API request
    $action = 'get_tasks';
    $page = '/project/100/';
    $request = $worksectionProvider->getAuthenticatedRequest(
        'GET',
        'https://domen.worksection.com/api/oauth2?action=' . $action . '&page=' . $page,
        $accessToken
    );
    try {
        $response = $worksectionProvider->getParsedResponse($request);
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        exit($e->getMessage());
    }
    var_dump($response);
}
```

For more information see the PHP League's general usage examples.

## License

The MIT License (MIT).
