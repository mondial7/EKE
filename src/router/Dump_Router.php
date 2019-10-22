<?php

	/**
	* Dump Router class
	*
	* Evaluate uri and load the right controller with a switch based on path segments
	*
	* Public Methods: route(), noRoute(), manyRoute(),
	*                 render(), loadController(),
	*                 setDefaultController(), setControllersExtension()
	*
	*/
	class Dump_Router {
		
		/**
		 * Controllers dir
		 *
		 * @var string
		 *
		 */
		private static $controllers_dir = "./app/controllers/";

		/**
		 * Default controller file extension
		 *
		 * @var string
		 *
		 */
		private static $controllers_ext = ".php";

		/**
		 * Set here a deafult controller to load in case of errors
		 *
		 * @var string
		 *
		 */
		private static $default_controller = "404.php";
		private static $default_error_message = "Ops, nothing is here!";

		/**
		 * Declare an empty array "routes" to store all the routes paths
		 *
		 * @var array [string]
		 *
		 */
		private static $routes = [];

		/**
		 * Declare an empty array "no routes" to store paths that require normal behaviour
		 *
		 * @var array [string]
		 *
		 */
		private static $no_routes = [];

		/**
		 * Set default controller
		 *
		 * @param string
		 * @return void
		 *
		 */
		public static function setDefaultController($value) {
			self::$default_controller = $value;
		}

		/**
		 * Set default controller
		 *
		 * @param string
		 * @return void
		 *
		 */
		public static function setControllersExtension($value) {
			self::$controllers_ext = $value;
		}

		/**
		 * Set paths that require normal behaviour
		 *
		 * @param string
		 * @return void
		 *
		 */
		public static function noRoute($path_segment) {
			self::$no_routes[] = $path_segment;
		}

		/**
		 * Add new route to the collection
		 *
		 * @param string
		 * @param array (optional)
		 * @return void
		 *
		 */
		public static function route($path_segment, $route_data = null) {
			
			// When route_data is not specified, 
		    // use the same path_segment as controller name
		    if ($route_data === null) {
		    	$route_data = ['controller' => $path_segment];
		    } else if (!isset($route_data['controller'])){
		    	$route_data['controller'] = $path_segment;	
		    }
			// Store the new route
			self::$routes[$path_segment] = $route_data;

		}

		/**
		 * Set multiple simple route
		 * 
		 * @param array [string]
		 * @return void
		 *
		 */
		public static function manyRoute($routes_arr) {
			
			foreach ($routes_arr as $route) {
				self::route($route);
			}

		}

		/**
		 * Evaluate URI and include the right controller
		 *
		 * @param string
		 * @param string (optional)
		 * @return void
		 *
		 */
		public static function render($uri, $controllers_dir = null) {
			
			// Require the controller
			require_once self::loadController($uri, $controllers_dir);

		}

		/**
		 * Load the controller
		 *
		 * @param string
		 * @param string (optional)
		 * @return string controller
		 *
		 */
		public static function loadController($uri, $controllers_dir = null) {

			// Set the controllers dir
			if ($controllers_dir === null) {
				$controllers_dir = self::$controllers_dir;
			}

			// @string uri to @array with 'path' and 'parameters'
			$uri = self::parseUriPath($uri);

			// @array of path segments
			$path = self::parseUriSegments($uri);

			$controller_name = null;
			$file_not_found__controller = $controllers_dir . self::$default_controller;

			// Check if normal behaviour is mandatory
			foreach (self::$no_routes as $std_path) {
				
				if ($path[0] == $std_path){

					// clena uri (avoid relative path failures)
					$uri = trim($uri, '/');
					
					if ( file_exists($uri) && !is_dir($uri) ) {
						return $uri;
					} else {
						return $file_not_found__controller;
					}

				}

			}

			// Check for path/route match
			foreach (self::$routes as $route_segment => $route_data) {

				if ($path[0] == $route_segment) {
					
					$controller_name = $route_data['controller'];
					// Set extra get parameters --> handle pretty url like '/category/product/'
					if (isset($route_data['pretty_parameters']) && 
						$route_data['pretty_parameters'] !== null) {
						self::setExtraGetParameters($route_data['pretty_parameters'], $path);
					}
					// Exit the loop
					break;

				}

			}

			$controller = $controllers_dir.$controller_name.self::$controllers_ext;

			// Default controller if none has been match
			// or if controller file not exists
			if (empty($controller_name) || !file_exists($controller)) {
				$controller = $file_not_found__controller;
			}

			return $controller;

		}

		/**
		 * Parse URI
		 * return array with path and optional get parameters
		 *
		 * @param string
		 * @return array [ uri path, uri parameters ]
		 *
		 */
		private static function parseUriPath($uri) {

			// Get the base of the uri
			$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
			// Remove base from uri string
			$uri = substr($uri, strlen($basepath));
			// Remove parameters from uri
			if (strstr($uri, '?')) {
				$uri = substr($uri, 0, strpos($uri, '?'));
			}
			// Clean the uri path (usefull for parseUriSegments)
			$uri_path = '/' . trim($uri, '/');

			return $uri_path;

		}

		/**
		 * Return an array with uri segments
		 *
		 * @param string
		 * @return string
		 *
		 */
		private static function parseUriSegments($uri) {
			
			$segments = explode('/', $uri);
			$clened_segments = [];

			// Clean up empty segments
			foreach ($segments as $segment) {
				if (trim($segment) != "") {
					$clened_segments[] = $segment;
				}
			}

			// Normalize path segment for home/landing page (http://yourwebsite.com/)
			if (count($clened_segments) === 0) {
				$clened_segments[0] = "/";
			}

			return $clened_segments;

		}

		/**
		 * Handle Pretty urls
		 * Parse segments of the uri path after the first
		 * and convert them in get parameters as define in the route
		 *
		 * @param string
		 * @param string
		 * @return void
		 *
		 */
		private static function setExtraGetParameters($parameters_arr, $path_arr){

			// Remove first element from array
			array_shift($path_arr);

			// Count numbers of parameters
			$parameters_count = count($parameters_arr);
			$path_count = count($path_arr);

			// Loop and set $_GET parameters
			for($i = 0; $i < $parameters_count; $i++) {
				$_GET[$parameters_arr[$i]] = ($i<$path_count && !empty($path_arr[$i])) ? $path_arr[$i] : "";
			}

		}

	}