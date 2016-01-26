<?php

	class SimpleCli
	{
		// Console handling
		private $mod = array("bold" => 1, "lowint" => 2, "underline" => 4, "blink" => 5, "reverse" => 7, "regular" => 0);
		private $color = array("black" => 30, "red" => 31, "green" => 32, "yellow" => 33, "blue" => 34, "purple" => 35, "cyan" => 36, "white" => 37);
		private $echo_out = true;

		private function ansi($str)
		{
			if ($this->echo_out)
				print $str;
			
			return $str;
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

		function keyPressed($limit = "*")
		{
			system("stty -icanon");
			system("stty -echo");
			while ($c = fread(STDIN, 1)) {
			
				if ($limit == "*")
				{
					system("stty echo");
					system("stty icanon");
					return $c;
				}
				else
				{
					$a = explode(",",$limit);
					if (in_array($c,$a))
					{
						system("stty echo");
						system("stty icanon");
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
