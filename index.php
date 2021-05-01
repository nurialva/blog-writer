<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type");

require_once 'vendor/autoload.php';
require_once 'config.php';


use GraphQL\GraphQL;
use GraphQL\Type\Schema as SchemaType;
use GraphQL\Error\FormattedError;
use GraphQL\Type\Definition\ObjectType;

define('BASE_URL', 'http://localhost:3000');

ini_set('display_errors', 0);

$debug = !empty($_GET['debug']);
if ($debug) {
    $phpErrors = [];
    set_error_handler(function($severity, $message, $file, $line) use (&$phpErrors) {
        $phpErrors[] = new ErrorException($message, 0, $severity, $file, $line);
    });
}


try {

     $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $appContext = [
        'user_id' => null, 
        'pdo' => $pdo 
    ];


    if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $raw = file_get_contents('php://input') ?: '';
        $data = json_decode($raw, true);
    } else {
        $data = $_REQUEST;
    }
    $data += ['query' => null, 'variables' => null];
    if (null === $data['query']) {
        $data['query'] = '{hello}';
    }

    require __DIR__ . '/types/PostType.php';
    require __DIR__ . '/Types.php';

    $queryType = new ObjectType([
        'name' => 'Query',
        'fields' => [
            'hello' => [
                'description' => 'QertasID',
                'type' => Types::string(),
                'resolve' => function() {
                    return 'Simple Blogging Platform!';
                }
            ],
            'posts' => [
                'description' => 'Data postingan',
                'type' => Types::listOf(Types::post()),
                'args' => [
                    'offset' => Types::int(),
                    'limit' => Types::int()
                ],
                'resolve' => function($rootValue, $args, $context) {
                    $pdo = $context['pdo'];
                    $limit = $args['limit'] ?: 10;
                    $offset = $args['offset'] ?: 0;

                    if ($limit > 50) $limit = 50;

                    $result = $pdo->query("select * from posts order by id desc limit {$limit} offset {$offset}");
		     	return $result -> fetchAll(PDO::FETCH_OBJ);

                }
            ],
        ]
    ]);

    $mutationType = new ObjectType([
        'name' => 'Mutation',
        'fields' => [
            'post' => [
                'description' => 'Post a text',
                'type' => Types::string(),
                'args' => [
                    'content' => Types::nonNull(Types::string()),
                    'author' => Types::string(),
                ],
                'resolve' => function($rootValue, $args, $context) {
                	
	                    $pdo = $context['pdo'];
	                    $author = 99;
	                    $id = mt_rand ();

	                    $content = $args['content'];

	                    
	                    if ( !empty ( $args['author'] ) ) {
	                    	$author = $args['author'];
	                    }

	                    if ( !empty ( $args['content'] ) ) {

	                    	$stmt = $pdo -> prepare (
	                    		"insert into posts (content,author,id) values (?,?,?)"
	                    	);
	                    	$stmt -> bindparam (1, $content);
	                    	$stmt -> bindparam (2, $author);
	                    	$stmt -> bindparam (3, $id);
	                    	$stmt -> execute ();

	                    	return "added";
	                    }
					else {

						return "error";
					}
					

                    }
            ]            
        ]
    ]);


    $schema = new SchemaType([
        'query' => $queryType,
        'mutation' => $mutationType,
        
    ]);

    $result = GraphQL::executeQuery(
        $schema,
        $data['query'],
        null,
        $appContext,
        (array) $data['variables']
    );

    if ($debug && !empty($phpErrors)) {
        $result['extensions']['phpErrors'] = array_map(
            ['GraphQL\Error\FormattedError', 'createFromPHPError'],
            $phpErrors
        );
    }
    $httpStatus = 200;
} catch (\Exception $error) {
    $httpStatus = 500;
    if (!empty($_GET['debug'])) {
        $result['extensions']['exception'] = FormattedError::createFromException($error);
    } else {
        $result['errors'] = [FormattedError::create('Unexpected Error')];
    }
}

header('Content-Type: application/json', true, $httpStatus);
echo json_encode($result);
