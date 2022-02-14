

# Introduction

A plugin for custom API routing. Made with love by Howatson+Co.


# Usage

There are two parts to using this plugin:
1. Defining API routes
2. Defining custom controllers for handling response logic

To do either of these, you will need to first access the Rest API `Server` class instance. This can be achieved either by calling the global scope function `HCRR` like this:
```
$server = HCRR();
```

Or, by accessing the global scope variable assigned:
```
global $hcrr;
```


## Defining Routes

You can define a route through the `registerRoute` method attached to the `Router` class, like so:
```
$hcrr->router->registerRoute("/test", "\TestController", "index");
```

The `registerRoute` method takes three arguments: 
- API URI pattern to be matched for this route to be executed. This can be a regex string. 
- Name of Controller to instantiate.
- Name of method attached to Controller to execute.

## Defining Controllers

When defining Controllers, adhere to the following rules:
- The controller must extend `HC\RestRoutes\Controller`
- Controller methods should be named `${name}_${httpVerb}`, where:
	- `name` is the name you pass to the route, and
	- `httpVerb` is the HTTP verb of the request (e.g `GET`, `POST`, `PUT`, etc)
- Use `HC\RestRoutes\Server::serveRequest` to send an API response

See example below:

```
class TestController extends HC\RestRoutes\Controller
{
  public function index_get()
  {
    HC\RestRoutes\Server::serveRequest(
      new HC\RestRoutes\Response(
        array(
          "success" => true,
          "message" => "custom controller has been applied!"
        )
      )
    );
  }
}
```

`HC\RestRoutes\Response` takes three arguments:
- `Data` - Data to be sent through
- `Status code` - HTTP status code to attach to the response. (default `200`)
- `Headers` - Any additional headers to attach to the response. (default `array()`)

Then, to register the Controller we can use the `ControllerFactory` class `register` method:

```
$server->router->controllerFactory->register("\TestController");
```

## Full example

Bringing it all together, an example could look like this:

```
class TestController extends HC\RestRoutes\Controller
{
  public function index_get()
  {
    HC\RestRoutes\Server::serveRequest(
      new HC\RestRoutes\Response(
        array(
          "success" => true,
          "message" => "custom controller has been applied!"
        )
      )
    );
  }
}

$server = HCRR();
$server->router->controllerFactory->register("\TestController");
$server->router->registerRoute("/test", "\TestController", "index");
```