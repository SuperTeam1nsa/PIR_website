<?php
//à modifier en déploiement
//define ('BASE_PATH','http://localhost/minisite/ex15/public/');

session_start();
class App {	
	// module, action et paramètres par défaut
	private $module = "";
	private $method = '';
	private $params = [];
    public function default_module($url){
        $this->module="main";
        $this->method='show';
        require_once (dirname(__FILE__).'/../'.($this->module).'/'.($this->module).'Controleur.php');
        // on crée une instance du controleur souhaité
		$controller = $this->module.'Controleur';
		$controller = new $controller;
		// si il y a des params dans l'url on les charge dans params
		$this->params = $url ? array_values($url) : [];
		// lancement de l'action
		call_user_func_array([$controller, $this->method], $this->params);
    }
	public function __construct()
	{
		// base path local
		define ('BASE_PATH', 'http://localhost/PIR_website/matt/public/');
		// traitement de l'URL
		$url = $this->parseUrl();
		// si le param 1 et le param 2 sont renseignés et que le param 1 est bien un module existant
		if (isset($url[0]) && isset($url[1]) && file_exists(dirname(__FILE__).'/../'.$url[0])
			&& !is_file(dirname(__FILE__)."/../".$url[0])){
			$moduleTemp = $url[0];
			$methodTemp = $url[1];
			unset($url[0]);
			unset($url[1]);
			
			require_once (dirname(__FILE__).'/../'.$moduleTemp.'/'.$moduleTemp.'Controleur.php');
			// si la méthode renseignée en param 2 est bien une méthode du module en param 1
			if (method_exists($moduleTemp."Controleur", $methodTemp)) {
				$this->module = $moduleTemp;
				$this->method = $methodTemp;
			}
			// sinon on charge le controleur par défaut score
			else {
				$this->default_module($url);
			}
		}
		// si les params ne sont pas renseignés on charge le controleur score 
		else{
			$this->default_module($url);
		}
		// on crée une instance du controleur souhaité
		$controller = $this->module.'Controleur';
		$controller = new $controller;
		// si il y a des params dans l'url on les charge dans params
		$this->params = $url ? array_values($url) : [];
		// lancement de l'action
		call_user_func_array([$controller, $this->method], $this->params);
	}



	public function parseUrl() {
 		$url = null;
		if(isset($_GET['url'])) {
			$url = explode('/',filter_var(rtrim($_GET['url'], '/'),FILTER_SANITIZE_URL));
		}
		return $url;
	}
}

?>
