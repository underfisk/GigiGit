## GigiGit Documentation

Searching repositories, authentication tokens, commits and much more related to git it's pretty simple to find, you'll just have to create a client to manage everything on this API.
Wrap [GitHub Repo API](http://developer.github.com/v3/repos/). All methods are described on that page.
This app was made for a specific software so if you want to use, you might have to add some changes on it because i just released the source to give an idea or to 
integrate on CodeIgniter 3

#### APP Configuration
If you're using CodeIgniter create a new config array with this example data:
```php
$config['ggg_settings'] = array(
    "client_id"     =>  "id",
    "client_secret" =>  "secret",
    "scope"         =>  "user%20repo"
);
```
Now if you are using pure PHP you simply pass by parameter to the constructor the following data:
```php
$client = new GitClient(array(
	'client_id'	=>	'id',
	'client_secret' => 'secret',
	'scope'	=>	'scopes'
));
```
P.S: This method also work without Codeigniter configuration but i strongly recommend to use CI Config system

#### PHP Usage - PHP (Not framework)
Initialise your GigiGit API Client object on PHP without framework
```php
require_once  'GitCore/GitClient.php';
use GitCore\GitClient  as GitClient;

$appClient = new GitClient();
```
### PHP Usage - CodeIgniter
Initialize your GigiGit in a library file by creating an instance of it(if you want just use my lib file)
```php
	private $client_instance = null;
	private $CI = null;
	public function __construct()
	{
		$this->CI = &get_instance();
		$this->client_instance = new GitClient($this->CI->config->item('git_info'));
	}

	public function getClient()
	{
		return $this->client_instance ?: new GitClient($this->CI->config->item('git_info'));
	}
```

### Generate your authentication token backlink and revoke

```php
echo  "<a href=".$client->GetAuthorizationURL()."> Authorizate our app </a></br>";
echo  "<a href=".$client->GetRevokeURL()."> Revoke our app </a>";
```

### Retrieve code after being redirected from authorization url
This code is very important, this with we will be able to get our Access Token the one who will allow us to communicate freely with GitHub
```php
$code = $_GET['code'];
```
or in codeigniter to escape the code
```php
$code = $this->input->get('code',true);
```


### Retrieve code after being redirected from authorization url
Recommended save it in database as we have by default or somewhere instead of being asking all the time to GitHub to generate new tokens
```php
//This app saves in database the github tokens with a key for user_id
$userToken = $client->Authenticate($code,$user_id);
```
or in codeigniter
```php
$userToken = $this->gigigit->getClient()->Authenticate($code,$user_id);
```

### RequestBaseMessage Object
This object is always returned with 2 important things which are Content and Headers of every response we do in this API.
To access them you'll have to use the following methods in this example
```php
//Example of repository request
$response = $client->repos->all('johnsmith');
//To check his headers
$headers = $response->getHeaders();
//Want them in json or array?
$headersArray = $response->getHeaders()->__toArray();
$headersJSON = $response->getHeaders()->__toJSON();
//Want the body? it's always json
$content = $response->getContent(); //to json and array works too
```
If you use CodeIgniter is likely the same but the client needs to be retrieved like this
```php
$response = $this->gigigit->getClient()->repos->all('johnsmith');
//...
```


### API Abstract classes references

This is basically the workflow of this API

```php
//Check GitClient objects instances for more info and also the classes to see the available Methods (if your IDE/texteditor doesn't show them)
$client->commits->method()
$client->repos->method()
$client->users->method()
...
```
or in Codeigniter
```php
$this->gigigit->getClient()->commits->method()
..
```

### API Example (Repos)

In this example we have a single repository, all for authenticated or search user in github.
```php
//Check GitClient objects instances for more info and also the classes to see the available Methods (if your IDE/texteditor doesn't show them)
$client->commits->method()
$client->repos->method()
$client->users->method()
...
```

```php
//Via token, the second parameter is required if you are passing a token
$repos = $client->repos->all($appClient->GetLogin($user_id),true);

//Via username
$reposByName $client->repos->all('johnsmith');
```

Returns an RequestBaseMessage object with couple information.

If you have another php framework you'll have to adapt it, in Codeigniter you add the GigiGit in third_party and add a library file to get instances

