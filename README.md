

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
$server->router->registerRoute("/test", "\TestController", "index");
```

The `registerRoute` method takes three arguments: 
- API URI pattern to be matched for this route to be executed. This can be a regex string. 
- Name of Controller to instantiate.
- Name of method attached to Controller to execute.

## Defining Controllers

When defining Controllers, adhere to the following rules:
- The controller must extend `\HC\RestRoutes\Abstracts\ControllerAbstract`
- Controller methods should be named `${name}_${httpVerb}`, where:
	- `name` is the name you pass to the route, and
	- `httpVerb` is the HTTP verb of the request (e.g `GET`, `POST`, `PUT`, etc)
- `Return` the data you want to respond with.
- Throw a `RestfulException` provided by the `RestRoutes` plugin to handle exceptions.

See example below:

```
class TestController extends \HC\RestRoutes\Abstracts\ControllerAbstract
{
  public function index_get()
  {
    $posts = get_posts();

    // This will send a 200 response to the client side
    // with the data payload attached
    if (!empty($posts)) {
      return $posts;
    }

    // Otherwise, you can handle exceptions like this
    throw new \HC\RestRoutes\Exceptions\NotFoundException("No posts found.");
  }
}
```

Next, we will need to register the controller.

To register the Controller, use the `ControllerFactory` class `register` method:

```
$server->router->controllerFactory->register("\TestController");
```

## Full example

Bringing it all together, an example could look like this:

```
class TestController extends \HC\RestRoutes\Abstracts\ControllerAbstract
{
  public function index_get()
  {
    $posts = get_posts();

    // This will send a 200 response to the client side
    // with the data payload attached
    if (!empty($posts)) {
      return $posts;
    }

    // Otherwise, you can handle exceptions like this
    throw new \HC\RestRoutes\Exceptions\NotFoundException("No posts found.");
  }
}

$server = HCRR();
$server->router->controllerFactory->register("\TestController");
$server->router->registerRoute("/test", "\TestController", "index");
```

## Extra

### Authorisation

To ensure an endpoint only gives the full payload to a user who is authorised, you can use the `validateAdmin` and `validateEditor` methods which are attached to the `\HC\RestRoutes\Abstracts\ControllerAbstract` class.

### Defining API prefix

The default API prefix is `/api/hcrr`. You can customise this by using the `setPrefix` method on the `\HC\RestRoutes\Router` class:
```
$server->router->setPrefix("/your/custom/prefix");
```