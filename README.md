# Worksection Provider for OAuth 2.0 Client

This package provides Worksection OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

```
composer require worksection/oauth2-worksection
```

## Usage

```php
use Worksection\OAuth2\Client\Provider\Worksection as WorksectionOauth2;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

$provider = new WorksectionOauth2([
    'clientId'                => 'yourId',   
    'clientSecret'            => 'yourSecret',
    'redirectUri'             => 'https://redirecturl.com/query'
]);


if (!$_REQUEST['code']) {
    $authorizationUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();

    header('Location: ' . $authorizationUrl);

} elseif ($_REQUEST['state']) {
    if ($_REQUEST['state'] !== $_SESSION['oauth2state']) {
        exit('Invalid state');
    } else {
        unset($_SESSION['oauth2state']);
    }
    
    // Access token
    try {
        $accessToken = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
    } catch (IdentityProviderException $e) {
        exit($e->getMessage());
    }

    // Resource info
    try {
        $resourceOwner = $provider->getResourceOwner($accessToken);
    } catch (IdentityProviderException $e) {
        exit($e->getMessage());
    }
        
        
    // Make some API request using Access Token
    $action = 'get_tasks';
    $page = '/project/100/';
    $request = $provider->getAuthenticatedRequest(
        'GET',
        'https://domen.worksection.com/api/oauth2?action=' . $action . '&page=' . $page,
        $accessToken
    );
    try {
        $response = $provider->getParsedResponse($request);
    } catch (IdentityProviderException $e) {
        exit($e->getMessage());
    }
    var_dump($response);
}
```

For more information see the PHP League's general usage examples.

## License

The MIT License (MIT).
