<?php

/**
 * 验证码类
 *
 * 可切换简体中文，英文数字
 * 支持ttf字体，并产生字符点干扰和线干扰，字体角度旋转，背景16进制色彩以及透明
 * 需要GD库和FreeType支持
 *
 * 测试环境 Debian6.0 PHP5.35 nginx0.8.54
 *
 * @author Kvoid
 * @copyright http://kvoid.com
 * @version 1.0
 * @access public
 * @example
 *
 * header('Content-type: image/png');
 * include('verify.php');
 * $v=new Verify(null,null,5);  //实列化，默认根据字符大小产生图像，产生5个字符
 * $v->ZH=true;  //打开中文验证码开关
 * $v->bg_alpha=127; //背景全透明，注意IE6不支持png透明，需写hack
 * $_SESSION['verify']=$v->show(); //字符串写入session，并得到返回的验证字串
 */

class dwVerify {
	/**
	 * 图像句柄
	 */
	private $img;
	/**
	 * 图像宽度
	 */
	private $w;
	/**
	 * 图像高度
	 */
	private $h;
	/**
	 * 背景颜色，默认为白色
	 */
	public $bg_color = 'FFF';
	/**
	 * 背景透明度，范围0-127，127为完全透明
	 */
	public $bg_alpha = 0;
	/**
	 * 干扰像素数量
	 */
	public $pts_num = 50;
	/**
	 * 干扰像素颜色
	 */
	private $pts_color;
	/**
	 * 干扰线条数量
	 */
	public $line_num = 3;
	/**
	 * 干扰线条颜色
	 */
	private $line_color;
	/**
	 * 验证字符数
	 */
	private $font_num;
	/**
	 * 验证字符大小(px)
	 */
	private $font_size;
	/**
	 * ttf字体路径，数组
	 */
	public $font_family = array('fonts/simkai.ttf');
	/**
	 * 简体中文开关
	 */
	public $ZH = false;
	/**
	 * 构造函数
	 * @param witdh  整型int (px)  实例化指定图像宽充，不指定根据字符大小自动计算
	 * @param height  整型int (px)  实例化指定图像高充，不指定根据字符大小自动计算
	 * @param font_num   整型int   产生字符个数
	 * @param font_size   整型int (px)  指定字符大小
	 */
	public function __construct($width = null, $height = null, $font_num = 4, $font_size = 14) {
		$this->font_num = $font_num;
		$this->w = $width;
		$this->h = $height;
		$this->font_size = $font_size;
	}
	/**
	 * 产生验证字符串
	 */
	public function code() {
		if ($this->ZH == true) {
			for ($i = 0; $i < $this->font_num; $i++) {
				//$str .= '%'.dechex(mt_rand(176,247)).'%'.dechex(mt_rand(160,254)); //此方法会生成生僻字
				$c1 = substr(str_shuffle('BCD'), 0, 1);
				$c2 = ($c1 == 'D') ? substr(str_shuffle('0123456'), 0, 1) : substr(str_shuffle('0123456789ABCDEF'), 0, 1);
				$c3 = substr(str_shuffle('ABCEF'), 0, 1);
				$c4 = ($c3 == 'A') ? substr(str_shuffle('123456789ABCDEF'), 0, 1) : (($c3 == 'F') ? substr(str_shuffle('0123456789ABCDE'), 0, 1) : substr(str_shuffle('0123456789ABCDEF'), 0, 1));
				$str .= '%' . $c1 . $c2 . '%' . $c3 . $c4;
			}
			//var_dump(mb_convert_encoding(urldecode($str), 'UTF-8', 'GBK'));die();
			return mb_convert_encoding(urldecode($str), 'UTF-8', 'GBK');
		} else  return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4);
	}
	/**
	 * 产生图像，指定宽高与背景颜色
	 */
	private function create() {
		if ($this->font_family == null) $this->font_family = mt_rand(1, 5);
		if ($this->w == null) $this->w = ($this->ZH) ? ($this->font_num + 1) * $this->font_size * 1.5 : ($this->font_num + 1) * $this->font_size;
		if ($this->h == null) $this->h = $this->font_size * 2;
		$this->img = imagecreate($this->w, $this->h);
		$bgcolors = $this->handlecolor($this->bg_color);
		imagecolorallocatealpha($this->img, $bgcolors[0], $bgcolors[1], $bgcolors[2], $this->bg_alpha);
	}
	/**
	 * 处理16进制颜色，返回RGB颜色数组
	 *
	 * @param color    数组array(int,int,int) 对应 R G B, 或字符串string 支持web安全色缩写
	 * @return array(int,int,int)
	 */
	private function handlecolor($color) {
		if (is_array($color)) return $color;
		$wordnum = strlen($color);
		switch ($wordnum) {
			case 3:
				return array(hexdec(substr($color, 0, 1) . substr($color, 0, 1)), hexdec(substr($color, 1, 1) . substr($color, 1, 1)), hexdec(substr($color, 2, 1) . substr($color, 2, 1)));
				break;
			case 6:
				return array(hexdec(substr($color, 0, 2)), hexdec(substr($color, 2, 2)), hexdec(substr($color, 4, 2)));
				break;
			default:
				die('Invalid arguments');
		}
	}
	/**
	 * 产生干扰线条
	 */
	private function makeline() {
		for ($i = 0; $i < $this->line_num; $i++) {
			$this->line_color = imagecolorallocate($this->img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imageline($this->img, mt_rand(0, $this->w), mt_rand(0, $this->h), mt_rand(0, $this->w), mt_rand(0, $this->h), $this->line_color);
		}
	}
	/**
	 * 产生干扰像素
	 */
	private function makepoint() {
		for ($i = 0; $i < $this->pts_num; $i++) {
			$this->pts_color = imagecolorallocate($this->img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagesetpixel($this->img, mt_rand(0, $this->w), mt_rand(0, $this->h), $this->pts_color);
		}
	}
	/**
	 * 生成图像
	 */
	/*public function show() {
		$this->create();
		$code=$this->code();
		var_dump($code);die();
		$font_family = $this->font_family[mt_rand(0, count($this->font_family) - 1)];
		for ($i = 0; $i < $this->font_num; $i++) {
			$fontcolor = imagecolorallocate($this->img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagettftext($this->img, $this->font_size, mt_rand(-30, 30), ($this->ZH) ? ($this->font_size / 2 + $this->font_size * $i) * 1.5 : $this->font_size / 2 + $this->font_size * $i, ($this->h + $this->
				font_size) / 2, $fontcolor, $font_family, mb_substr($code, $i, 1, 'UTF-8'));
		}
		$this->makepoint();
		$this->makeline();
		imagepng($this->img);
		imagedestroy($this->img);
		return $code;
	}*/
	public function show() {
		$this->create();
		$code=$this->code();
		//var_dump($code);die();
		$font_family = $this->font_family[mt_rand(0, count($this->font_family) - 1)];
		for ($i = 0; $i < $this->font_num; $i++) {
			//die($font_family);
			$fontcolor = imagecolorallocate($this->img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagettftext($this->img, $this->font_size, mt_rand(-30, 30), ($this->ZH) ? ($this->font_size / 2 + $this->font_size * $i) * 1.5: $this->font_size / 2 + $this->font_size * $i, ($this->h + $this->
				font_size) / 2, $fontcolor, $font_family, mb_substr($code, $i, 1, 'UTF-8'));
		}
		$this->makepoint();
		$this->makeline();
		imagepng($this->img);
		imagedestroy($this->img);
		return $code;
	}
}

//error_reporting(0);
//header('Content-type: image/png');
//$v=new Verify(null,null,5);  //实列化，默认根据字符大小产生图像，产生5个字符
//$v->ZH=true;  //打开中文验证码开关
//$v->bg_alpha=127; //背景全透明，注意IE6不支持png透明，需写hack
//$_SESSION['verify']=$v->show(); //字符串写入session，并得到返回的验证字串
?>