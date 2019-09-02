<?php

class Obfuscator{
	static $code,$mask,$interval,$option=0,$expireTime=0,$domainNames=array(),$search=array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s','/<!--(.|\s)*?-->/'),$replace=array('>','<','\\1','');

	static function getMask(){ return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),0,9); }

	static function hashIt($s){
		for($i=0;$i<strlen(self::$mask);++$i) $s = str_replace("$i",self::$mask[$i],$s);
		return $s;
	}

	static function prepare(){
		if (count(self::$domainNames) > 0) {
			$code = "if(window.location.hostname==='".self::$domainNames[0]."' ";
			for ($i=1;$i<count(self::$domainNames);$i++)
				$code .= "|| window.location.hostname==='".self::$domainNames[$i]."' ";
			self::$code = $code."){".self::$code."}";
		}
		if (self::$expireTime > 0)
			self::$code = 'if((Math.round(+new Date()/1000)) < '.self::$expireTime.'){'.self::$code.'}';
	}

	static function encodeIt(){
		self::prepare();
		for ($i=0,$s='',$l=strlen(self::$code);$i<$l;++$i)
			$s .= self::hashIt(base_convert(ord(self::$code[$i]) + self::$interval, 10, self::$option)) . self::$mask[self::$option];
		return $s;
	}

	static function Obfuscate($c,$h=0,$t=0,$d=0){
		self::$code = $h ? self::html2Js(self::cleanHtml($c)) : self::cleanJS($c);
		if($t) self::setExpiration(is_numeric($t) ? $t : strtotime($t));
		if ($d){
			if (is_array($d)){
				foreach($d as $a) self::addDomainName($a);
			}else{
				self::addDomainName($d);
			}
		}
		self::$mask = self::getMask();
		self::$interval = rand(1,50);
		self::$option = rand(2,8);
		$r = rand(0,99);
		$z = rand(0,99);
		return "var _0xc{$r}e=[\"\",\"\x73\x70\x6C\x69\x74\",\"\x30\x31\x32\x33\x34\x35\x36\x37\x38\x39\x61\x62\x63\x64\x65\x66\x67\x68\x69\x6A\x6B\x6C\x6D\x6E\x6F\x70\x71\x72\x73\x74\x75\x76\x77\x78\x79\x7A\x41\x42\x43\x44\x45\x46\x47\x48\x49\x4A\x4B\x4C\x4D\x4E\x4F\x50\x51\x52\x53\x54\x55\x56\x57\x58\x59\x5A\x2B\x2F\",\"\x73\x6C\x69\x63\x65\",\"\x69\x6E\x64\x65\x78\x4F\x66\",\"\",\"\",\"\x2E\",\"\x70\x6F\x77\",\"\x72\x65\x64\x75\x63\x65\",\"\x72\x65\x76\x65\x72\x73\x65\",\"\x30\"];function _0xe{$z}c(d,e,f){var g=_0xc{$r}e[2][_0xc{$r}e[1]](_0xc{$r}e[0]);var h=g[_0xc{$r}e[3]](0,e);var i=g[_0xc{$r}e[3]](0,f);var j=d[_0xc{$r}e[1]](_0xc{$r}e[0])[_0xc{$r}e[10]]()[_0xc{$r}e[9]](function(a,b,c){if(h[_0xc{$r}e[4]](b)!==-1)return a+=h[_0xc{$r}e[4]](b)*(Math[_0xc{$r}e[8]](e,c))},0);var k=_0xc{$r}e[0];while(j>0){k=i[j%f]+k;j=(j-(j%f))/f}return k||_0xc{$r}e[11]}eval(function(h,u,n,t,e,r){r=\"\";for(var i=0,len=h.length;i<len;i++){var s=\"\";while(h[i]!==n[e]){s+=h[i];i++}for(var j=0;j<n.length;j++)s=s.replace(new RegExp(n[j],\"g\"),j);r+=String.fromCharCode(_0xe{$z}c(s,e,10)-t)}return decodeURIComponent(escape(r))}(\"".self::encodeIt()."\",".rand(1,100).",\"".self::$mask."\",".self::$interval.",".self::$option.",".rand(1,60)."))";
	}

	static function setExpiration($expireTime){
		if (strtotime($expireTime)){
			self::$expireTime = strtotime($expireTime);
			return true;
		}
		return false;
	}

	static function addDomainName($d){
		if (self::isValidDomain($d)){
			self::$domainNames[] = $d;
			return true;
		}
		return false;
	}

	static function isValidDomain($d){ return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i",$d) && preg_match("/^.{1,253}$/",$d) && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/",$d)); }
	static function html2Js($c){ return "document.write('".addslashes(preg_replace(self::$search,self::$replace,$c)." ")."');"; }
	static function cleanHtml($c){ return preg_replace('/<!--(.|\s)*?-->/','',$c); }
	static function cleanJS($c){ return preg_replace(self::$search,self::$replace,preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/','',$c)); }
}