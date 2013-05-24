<?php
/*
Copyright (c) 2009, Francisco Facioni
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * The names of its contributors may not be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY Francisco Facioni ''AS IS'' AND ANY
EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL Francisco Facioni BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

namespace PHPTrie;

class Trie
{
	private $trie = array();
	private $value = null;
	
	function Trie($value = null){
		$this->value = $value;
	}
	
	function add($string,$value,$overWrite=true){
		if(empty($string)){
			if(is_null($this->value) || $overWrite){
				$this->value = $value;
			}
			
			return;
		}
		
		foreach($this->trie as $prefix => $trie){
			$prefixLength = strlen($prefix);
			$head = substr($string,0,$prefixLength);
			$headLength = strlen($head);
			
			$equals = true;
			$equalPrefix = "";
			for($i= 0;$i<$prefixLength;++$i){
				//Split
				if($i >= $headLength){
					$equalTrie = new Trie($value);
					$this->trie[$equalPrefix] = $equalTrie;
					$equalTrie->trie[substr($prefix,$i)] = $trie;
					unset($this->trie[$prefix]);
					return;
				}
				else if($prefix[$i] != $head[$i]){
					if($i > 0){
						$equalTrie = new Trie();
						$this->trie[$equalPrefix] = $equalTrie;
						$equalTrie->trie[substr($prefix,$i)] = $trie;
						$equalTrie->trie[substr($string,$i)] = new Trie($value);
						unset($this->trie[$prefix]);
						return;
					}
					$equals = false;
					break;
				}
				
				$equalPrefix .= $head[$i];
			}
			
			if($equals){
				$trie->add(substr($string,$prefixLength),$value,$overWrite);
				return;
			}
		}
		
		$this->trie[$string] = new Trie($value);
	}
	
	private function searchTrie($string){
		if(empty($string)){
			return array($string,$this);
		}
		
		$stringLength = strlen($string);
		foreach($this->trie as $prefix => $trie){		
			$prefixLength = strlen($prefix);
			if($prefixLength > $stringLength){
				$prefix = substr($prefix,0,$stringLength);
				if($prefix == $string){
					return array($string,$this);
				}
			}
			$head = substr($string,0,$prefixLength);
			
			if($head == $prefix){
				return $trie->searchTrie(substr($string,$prefixLength));
			}
		}
		
		return null;
	}
	
	function search($string){
		if(empty($string)){
			return $this->value;
		}
		
		foreach($this->trie as $prefix => $trie){		
			$prefixLength = strlen($prefix);
			$head = substr($string,0,$prefixLength);
			
			if($head == $prefix){
				return $trie->search(substr($string,$prefixLength));
			}
		}
		
		return null;
	}
	
	function searchMultiple($array,$delimeter=' '){
		$size = count($array);
		$value = null;
		
		for($j=0;$j<$size;++$j){
			$trie = $this;
			$delim = '';
			$key = '';
			
			for($i=$j;$i<$size;++$i){
				$key .= $delim.$array[$i];
				$ret = $trie->searchTrie($key);
				if(is_null($ret)){
					break;
				}
				
				$trie = $ret[1];
				$key = $ret[0];
				$delim = $delimeter;
				if(!is_null($trie->value)){
					$value = $trie->value;
				}
			}
			
			if(!is_null($value)){
				return $value;
			}
		}
		
		return null;
	}
}