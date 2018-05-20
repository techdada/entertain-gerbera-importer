<?php
$properties=array();

/**
 * Simple properties class
 *
 * @author dada
 *
 */
class Properties {
        public static function init($cfile) {
                global $properties;
                if (file_exists($cfile)) {
                        $cnf=file_get_contents($cfile);
                        foreach (explode("\n",$cnf) as $line) {
                                $line = trim($line);
				if (!$line) continue; //empty line skip
				if ($line[0]=='#') continue; //comment skip
                                $t=explode('=',$line);
                                $key = array_shift($t);
                                $value = join('=',$t);
                                $properties[$key]=$value;
                                $t=null;
                        }
                        return true;
                } else {
                        echo 'File does not exist:'.$cfile;
                        return false;
                }
        }
        public static function get($key) {
                global $properties;
                if (array_key_exists($key,$properties)) {
                        return $properties[$key];
                }
        }
}
