<?php

	class SimpleCli
	{
		// Console handling
		private $mod = array("bold" => 1, "lowint" => 2, "underline" => 4, "blink" => 5, "reverse" => 7, "regular" => 0);
		private $color = array("black" => 30, "red" => 31, "green" => 32, "yellow" => 33, "blue" => 34, "purple" => 35, "cyan" => 36, "white" => 37);
		private $echo_out = true;

		private $local_echo;

		public function __construct()
		{
			$this->echoOff();
		}

		public function __destruct()
		{
			$this->echoOn();
		}

		private function ansi($str)
		{
			if ($this->echo_out)
				print $str;
			
			return $str;
		}

		public function echoOff()
		{
			system("stty -icanon");
			system("stty -echo");
			$this->local_echo = false;
		}

		public function echoOn()
		{
			system("stty icanon");
			system("stty echo");
			$this->local_echo = true;
		}

		public function noEcho()
		{
			$this->echo_out = false;
		}

		function setColor($color, $modifier = "regular")
		{
			return $this->ansi("\e[".$this->mod[$modifier].";".$this->color[$color]."m");
		}

		function setBgColor($color)
		{
			return $this->ansi("\e[".($this->color[$color]+10)."m");
		}

		function gotoXY($x,$y)
		{
			return $this->ansi("\e[".$x.";".$y."f");
		}

		function savePos()
		{
			return $this->ansi("\e[s");
		}

		function gotoPos()
		{
			return $this->ansi("\e[u");
		}

		function reset()
		{
			return $this->ansi("\e[0m");
		}

		function keyPressed()
		{
			$result = stream_select($r = array(STDIN), $w = NULL, $e = NULL, 0);
		//	if($result === false) throw new Exception('stream_select failed');
			if($result === 0) return false;
		//	$data = stream_get_line(STDIN, 1);
			return true;
		}
		function readKey()
		{
			return stream_get_line(STDIN, 1);
		}

		function waitForKey($limit = "*",$ignoreCase = false)
		{
			while ($c = fread(STDIN, 1)) {
			
				if ($limit == "*")
				{
					return $c;
				}
				else
				{
					$a = explode(",",strtoupper($limit));
					if (in_array(strtoupper($c),$a))
					{
						return $c;
					}
				}
			}
		}

		function getPassword($start = "*")
		{
			$ret="";
			while ($c = $this->keyPressed())
			{
				if (($c == chr(13)) or ($c == chr(10)))
					return $ret;
				if ($c == chr(127))
				{
					print "\e[D \e[D";
					$ret=substr($ret,0,strlen($ret)-1);
				} else {
					print "*";
					$ret.=$c;
				}
			}

		}
		
		// CLI Parameter handling 

		function addFlag($flag,$short,$description)	// i.e.  -a or --all-files
		{
		
		}

		function addParm($parm,$short,$description)	// i.e.  -a <foo> --add <foo>  
		{
		}

	}
/*
	$cli = new Simplecli();
	$cli->noEcho();
	print $cli->setColor("blue","bold")."FUCKING FOO".$cli->setBgColor("red")."BAR".$cli->reset()." HAHAHA \n";

	print "\n\nTast din kode:";
print	$cli->getPassword();
die("-");
	print "\n\nTast 5:";
	print $cli->keyPressed(chr(27));

	print "\n\nLad os alle tælle:".$cli->savePos();

	for ($i=0; $i<=2000; $i++)
	{
		print $cli->gotoPos().$i;
		usleep(500);
	}
	
	print $cli->gotoXY(25,25)."FOOBAR";
*/
?>
