<?php
use Dflydev\FigCookies;
// Lista dos os Produtos
$app->get('/produtos', function ($request, $response, $args) {
	$dataBaseProducts = Array(
		Array("id"=>1,"nome"=>"biscoito","marca"=>"Mabel"),
		Array("id"=>2,"nome"=>"arroz","marca"=>"Tio joão"),
		Array("id"=>3,"nome"=>"leite","marca"=>"Manajoara")
		);
	
	$session = new \SlimSession\Helper;
  	if(!$session->exists('produtos')){
		$session->produtos = serialize($dataBaseProducts);
  	}else{
  		$dataBaseProducts = unserialize($session['produtos']);
  	}
    
	$response = $response->withHeader('Content-type', 'application/json');
	$response = $response->withJson($dataBaseProducts);
    return $response;
});

// Cadastrada novo Produto
$app->post('/produtos', function ($request, $response, $args) {
    
	$produto = $request->getParsedBody();

	$session = new \SlimSession\Helper;

    if($session->exists('produtos')){
		$dataBaseProducts = unserialize($session['produtos']);
		$num = count($dataBaseProducts);
		$key = $dataBaseProducts[$num-1]['id'];
		$produto['id'] = $key+1;
		$dataBaseProducts[]= $produto;
		$session->produtos = serialize($dataBaseProducts);
  	}

	$response = $response->withHeader('Content-type', 'application/json');
	$response = $response->withJson($produto);
    return $response;
});

// Lista os dados de um único produto
$app->get('/produtos/{id}', function ($request, $response, $args) {
    
	$session = new \SlimSession\Helper;
	$product = Array();

  	if($session->exists('produtos')){
		$dataBaseProducts = unserialize($session['produtos']);
		foreach ($dataBaseProducts as $key => $value) {
			if($value['id'] == $args['id']){
				$product = $value;
				break;
			}
		}
  	}
    
	$response = $response->withHeader('Content-type', 'application/json');
	$response = $response->withJson($product);

    return $response;
});
// Atualiza os dados de um único produto
$app->put('/produtos/{id}', function ($request, $response, $args) {
    
	$produto = $request->getParsedBody();
	unset($produto['_METHOD']);
	$session = new \SlimSession\Helper;

  	if($session->exists('produtos')){
		$dataBaseProducts = unserialize($session['produtos']);
		foreach ($dataBaseProducts as $key => $value) {
			if($value['id'] == $args['id']){
				$produto['id'] = $args['id'];
				$dataBaseProducts[$key] = $produto;
				break;
			}
		}
		$session->produtos = serialize($dataBaseProducts);
  	}


	$response = $response->withHeader('Content-type', 'application/json');
	$response = $response->withJson($produto);
    return $response;
});

// Exclui um único produto
$app->delete('/produtos/{id}', function ($request, $response, $args) {

   	$session = new \SlimSession\Helper;

  	if($session->exists('produtos')){
		$dataBaseProducts = unserialize($session['produtos']);
		foreach ($dataBaseProducts as $key => $value) {
			if($value['id'] == $args['id']){
				unset($dataBaseProducts[$key]);
				break;
			}
		}
		$session->produtos = serialize($dataBaseProducts);
  	}

	$response = $response->withHeader('Content-type', 'application/json');
	$response = $response->withJson($dataBaseProducts);
    return $response;
});


$app->get('/', function ($request, $response, $args) {
    return $this->renderer->render($response, 'index.phtml', $args);
});