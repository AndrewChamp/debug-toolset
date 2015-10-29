<?php

/**
 * The MIT License (MIT)
 * Copyright (c) 2013-2015 Andrew Champ
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sub-license,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
 * NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * Please note that this class's methods & properties won't print to screen
 * if your IP address isn't registered in the $development property array.
 */

	class debug{

		private static $instance;

		public $development = array(
			'Work'			=> '123.34.678.',	//Note: You can leave the ending octet blank to act as a "wildcard" for IP blocks.
			'Home'			=> '98.765.43.21',
			'Cafe'			=> '101.01.010.1'
		);
		public $ip;
		public $dev = false;


		public function __construct(){
			$this->ip = $_SERVER['REMOTE_ADDR'];
			$this->dev = $this->isDevIP($this->ip);
			$this->noCaching();
			$this->developerMode();
		}


		/**
		 * Singleton Pattern.
		 * Allows for reusing the initial instanited object.
		 */
		public static function obtain(){
			if(!self::$instance)
				self::$instance = new debug();
			return self::$instance;
		}


		/**
		 * Development IP Check
		 * Checks if the user is using a development IP, for viewing debugging output.
		 */
		public function isDevIP($ip){
			if(in_array($ip, $this->development)):
				return true;
			else:
				foreach($this->development as $needle):
					if(strpos($ip, $needle) !== false):
						return true;
					endif;
				endforeach;
			endif;
			
			return false;
		}


		/**
		 * Checks for PHP version number.
		 */
		 public function versionCheck($version){
			if(version_compare(phpversion(), $version, '<='))
				throw new Exception('Your PHP version is v'.phpversion().', and needs to be above v'.$version.' in order for this framework to work properly.');
		}


		/**
		 * Prints an array.
		 */
		public function printArray($bugger){
			if(!$this->dev)
				return false;
				
			$this->pre($bugger);
		}


		/**
		 * Prints all the user defined defines.
		 */
		public function allDefines(){
			if(!$this->dev)
				return false;
				
			$defines = get_defined_constants(true);
			foreach($defines['user'] as $k => $v):
				$all .= $k.': '.$v."\n";
			endforeach;
			$this->pre($all);
		}


		/**
		 * Displays all the properties in the class.
		 */
		public function allVars($class){
			if(!$this->dev)
				return false;
				
			$this->pre(get_object_vars($class));
		}


		/**
		 * Displays all the methods in the class.
		 */
		public function allMethods($class){
			if(!$this->dev)
				return false;
				
			$this->pre(get_class_methods($class));
		}


		/**
		 * Syntax highlights code to screen.
		 */
		public function highlighter($file){
			if(!$this->dev)
				return false;
				
			if(file_exists($file)):
				$lines = implode(range(1, count(file($file)) + 10), '<br />');
				$content = highlight_file($file, true);
				print '<style>
						#codeWrapper{width:100%; margin:0 auto; padding:0; overflow:hidden; background:#111;}
						#codeWrapper .codeNum{float:left; color:#666; text-align:right; margin:0; padding:0 1% 0 0; border-right:1px solid #666; width:6%;}
						#codeWrapper .codeContent{float:left; width:91%; padding:0 0 0 1%; margin:0;}
						#codeWrapper .codeNum, #codeWrapper .codeContent{vertical-align:top; font-size:12px; font-family:monospace; background:#111; text-shadow:1px 1px 0 #000; word-wrap:break-word;}
						#codeWrapper span[style="color: #0000BB"]{color:rgb(129, 230, 255)!important;}
						#codeWrapper span[style="color: #007700"]{color:rgb(0, 224, 0)!important;}
						#codeWrapper span[style="color: #FF8000"]{color:rgb(255, 143, 0)!important;}
						#codeWrapper span[style="color: #000000"]{color:rgb(255, 255, 255)!important;}
					</style>';
				print "<div id=\"codeWrapper\"><div class=\"codeNum\">\n$lines\n</div><div class=\"codeContent\">\n$content\n</div></div>";
			endif;
		}


		/**
		 * Syntax highlight a php string.
		 */
		public function highlight($str){
			if(!$this->dev)
				return false;
			
			return highlight_string($str);
		}


		/**
		 * Prevents caching when in Developer Mode.
		 */
		private function noCaching(){
			if(!$this->dev)
				return false;
				
			header('Expires: Thu, 31 May 1984 08:00:00 EST');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Cache-Control: pre-check=0, post-check=0, max-age=0');
			header('Pragma: no-cache');
		}


		/**
		 * Displays on screen when you're in developer mode.
		 * Note: your IP has to be registered above to be in developer mode.
		 */
		private function developerMode(){
			if(!$this->dev)
				return false;
			
			print '<div style="z-index:99999; top:0; right:0; background:#000; box-shadow:0 0 10px #000; border-left:1px solid #999; border-bottom:1px solid #999; border-radius:0 0 0 7px; position:fixed; color:#FFF; padding:5px 10px; font-size:12px; font-family:monospace;">Developer Mode</div>';
		}


		/**
		 * Formats the array by putting the 'pre' tags around it.
		 */
		private function pre($array){
			print '<pre>';
			print_r($array);
			print '</pre>';
		}


		/**
		 * Prints to screen all the php files that make up your framework.
		 * Helps the developer by quickly referencing properties and methods in their framework.
		 */
		public function debugBar($directories=array()){
			if(!$this->dev)
				return false;
			
			print '<style>
				#debugBar{width:100%; font-family:monospace; font-size:12px; margin:0; padding:0; list-style-type:none; background:#000; border-bottom:1px solid #666; border-top:1px solid #666; clear:both; overflow:hidden; text-align:center;}
				#debugBar li{display:inline-block;}
				#debugBar li a{padding:5px 10px; color:rgb(255, 143, 0); text-decoration:none; display:block;}
			</style>';
			$result = '<ul id="debugBar">';
			foreach($directories as $dir):
				$paths .= $dir.'*.php,';
			endforeach;
			foreach(glob('{'.rtrim($paths).'}', GLOB_BRACE) as $file):
				$result .= '<li><a href="?output='.$file.'#debugBar">'.basename($file).'</a></li>';
			endforeach;
			print $result.'</ul>';
			$this->highlighter($_GET['output']);
		}


	}

?>