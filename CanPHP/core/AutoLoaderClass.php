<?php
namespace CanPHP/Core/AutoloaderClass;
class AutoloaderClass extends cpObject{
	public function register(){
        spl_autoload_register(array($this, 'loadClass'));
    }
    
	/**
     * 注册一个命名空间
     *
     * @param string       $namespace 命名空间
     * @param array|string $paths     命名空间对应的目录
     */		
    public function registerNamespace($namespace, $paths){
		$this->namespaces[$namespace] = (array) $paths;
    }

    public function loadClass($class){
        if ( ($this->apc && ($file = $this->findFileInApc($class))) or
            ($file = $this->findFile($class))){
            require $file;
        }
    }
	
    protected function findFileInApc($class){
        if (false === $file = apc_fetch($this->apc_prefix.$class)) {
            apc_store($this->apc_prefix.$class, $file = $this->findFile($class));
        }

        return $file;
    }

    public function findFile($class){
        // Remove first backslash
        if ('\\' == $class[0]){
            $class = substr($class, 1);
        }

        if (false !== ($pos = strrpos($class, '\\')) ){
            $namespace = substr($class, 0, $pos);
			$className = substr($class, $pos + 1);
			
            foreach ($this->namespaces as $ns => $dirs){
                //Don't interfere with other autoloaders
                if (0 !== strpos($namespace, $ns)){
                    continue;
                }

                foreach ($dirs as $dir){
                    $file = $dir.DIRECTORY_SEPARATOR.
                                 str_replace('\\', DIRECTORY_SEPARATOR, $namespace).
                                 DIRECTORY_SEPARATOR.
                                 $className.'.php';

                    if (file_exists($file)){
                        return $file;
                    }
                }
            }           
        }
    }	
	
}