WebLoader
=========

Komponenta pro na��t�n� CSS a JS soubor�

Autor: Jan Marek
Licence: MIT

Pou�it�
-------

Presenter:

	<?php

	use WebLoader\JavaScriptLoader;
	use WebLoader\VariablesFilter;
	use WebLoader\CssLoader;

	abstract class BasePresenter extends Presenter {

		protected function createComponentJs() {
			$js = new JavaScriptLoader;

			$js->tempUri = Environment::getVariable("baseUri") . "data/webtemp";
			$js->tempPath = WWW_DIR . "/data/webtemp";
			$js->sourcePath = WWW_DIR . "/js";

			$js->filters[] = new VariablesFilter(array(
				// texyla
				"baseUri" => Environment::getVariable("baseUri"),
				"texylaPreviewPath" => $this->link(":Texyla:preview"),
				"texylaFilesPath" => $this->link(":Texyla:listFiles"),
				"texylaFilesUploadPath" => $this->link(":Texyla:upload"),
			));

			return $js;
		}


		protected function createComponentCss() {
			$css = new CssLoader;

			$css->sourcePath = WWW_DIR . "/css";
			$css->tempUri = Environment::getVariable("baseUri") . "data/webtemp";
			$css->tempPath = WWW_DIR . "/data/webtemp";

			$css->filters[] = function ($code) {
				return cssmin::minify($code, "remove-last-semicolon");
			};

			return $css;
		}

	}

	?>

�ablona:

	{widget js 'jquery.js', 'texyla.js', 'web.js'}
	{widget css 'reset.css', 'page.css', 'libs/texyla.css', 'libs/jqueryui.css'}
