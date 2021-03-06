<?php
namespace Router\Tests;

use PHPUnit_Framework_TestCase;
use Router\Request;
use Router\Response;
use Router\Router;

abstract class AbstractKleinTest extends PHPUnit_Framework_TestCase
{
    /**
     * The automatically created test Router instance
     * (for easy testing and less boilerplate)
     *
     * @var \Router\Router;
     */
    protected $klein_app;

    /**
     * Setup our test
     * (runs before each test)
     *
     * @return void
     */
    protected function setUp()
    {
        // Create a new klein app,
        // since we need one pretty much everywhere
        $this->klein_app = new Router();
    }

    /**
     * Quick method for dispatching and returning our output from our shared Router instance
     *
     * This is mostly useful, since the tests would otherwise have to make a bunch of calls
     * concerning the argument order and constants. DRY, bitch. ;)
     *
     * @param Request $request Custom Router "Request" object
     * @param Response $response Custom Router "Response" object
     * @return mixed The output of the dispatch call
     */
    protected function dispatchAndReturnOutput($request = null, $response = null)
    {
        return $this->klein_app->dispatch(
            $request,
            $response,
            false,
            Router::DISPATCH_CAPTURE_AND_RETURN
        );
    }

    /**
     * Runs a callable and asserts that the output from the executed callable
     * matches the passed in expected output
     *
     * @param mixed $expected The expected output
     * @param callable $callback The callable function
     * @param string $message (optional) A message to display if the assertion fails
     * @return void
     */
    protected function assertOutputSame($expected, $callback, $message = '')
    {
        // Start our output buffer so we can capture our output
        ob_start();

        call_user_func($callback);

        // Grab our output from our buffer
        $out = ob_get_contents();

        // Clean our buffer and destroy it, so its like no output ever happened. ;)
        ob_end_clean();

        // Use PHPUnit's built in assertion
        $this->assertSame($expected, $out, $message);
    }

    /**
     * Loads externally defined routes under the filename's namespace
     *
     * @param Router $app_context The application context to attach the routes to
     * @return array
     */
    protected function loadExternalRoutes(Router $app_context = null)
    {
        // Did we not pass an instance?
        if (is_null($app_context)) {
            $app_context = $this->klein_app ?: new Router();
        }

        $route_directory = __DIR__ . '/routes/';
        $route_files = scandir($route_directory);
        $route_namespaces = array();

        foreach ($route_files as $file) {
            if (is_file($route_directory . $file)) {
                $route_namespace = '/' . basename($file, '.php');
                $route_namespaces[] = $route_namespace;

                $app_context->with($route_namespace, $route_directory . $file);
            }
        }

        return $route_namespaces;
    }
}
