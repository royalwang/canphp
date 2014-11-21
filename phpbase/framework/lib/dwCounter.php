<?php
class dwCounter
{
	private $redisHost = COUNTER_REDIS_HOST;
	private $redisPort = COUNTER_REDIS_PORT;
	static  $redisConnection = null;


	function __construct()
	{
		if(!self::$redisConnection)
		{
			try {
			$redis = new Redis();
			$redis->connect($this->redisHost,$this->redisPort,20);
			if($redis){
				self::$redisConnection= $redis;
			}
		}catch (Exception $e)
		{
			file_put_contents(dirname(__FILE__)."/../log/CounterException.log",date("Y-m-d H:i:s")." Redis Connection Exception host: {$this->redisHost} - port: {$this->redisPort}  - message: {$e->getMessage()} \r\n",FILE_APPEND);
		}
	  }
   }

	/**
	 * 计数加一操作
	 *
	 * @param string $domain
	 * @param string $tag
	 * @param string $step
	 * @param string $timestamp
	 */
	public function incr($domain,$tag,$step = 1,$timestamp = '')
	{
		if(empty($timestamp)){
			$timestamp = time();
		}

		$hashKey = $this->getHashKey($domain,$tag,$timestamp);
		$hashSubKey = $this->getSubHashKey($timestamp);
		return self::$redisConnection->hIncrBy($hashKey, $hashSubKey, $step);
	}

	/**
	 * 返回年的总计数
	 *
	 * @param string $domain lolbox
	 * @param string $tag crash
	 * @param string $year 2007
	 * @return int 总数量
	 */
	public function getCountByYear($domain,$tag,$year)
	{
		$total = 0;
		$keys = self::$redisConnection->keys("$domain:$tag:$year:*");
		if(count($keys)<0)
			return null;
		foreach ($keys as $item)
		{
			$total += $this->countTotalByHashKey($item);
		}
		return $total;
	}

	/**
	 * 统计年的访问计数.
	 *
	 * @param string $domain
	 * @param string $tag
	 * @return array year=>count
	 */

	public function getYearCountList($domain,$tag)
	{
		$resultList = array();
		$years  = array('2012,2013,2014,2015,2016');
		foreach ($years as $year)
		{
			$total = $this->getCountByYear($domain,$tag,$year);
			if($total)
				$resultList[$year] = $total;
		}
		return $resultList;
	}


	/**
	 * 返回按年月的总计数
	 *
	 * @param string $domain
	 * @param string $tag
	 * @param string $year
	 * @param string $month 09
	 * @return int
	 */
	public function getCountByYearMonth($domain,$tag,$year,$month)
	{

		$total = 0;
		$keys = self::$redisConnection->keys("$domain:$tag:$year:$month:*");
		//print_r($keys);
		if(count($keys)<0)
			return null;

		foreach ($keys as $item)
		{
			$total +=$this->countTotalByHashKey($item);
		}
		return $total;
	}

	/**
	 * 以月为单位，统计每月访问情况
	 *
	 * @param string $domain
	 * @param string $tag
	 * @param string $year
	 * @return array month => count
	 */
	public function getMonthCountList($domain,$tag,$year)
	{
		$resultList = array();
		$months = array('01','02','03','04','05','06','07','08','09','10','11','12');
		foreach ($months as $month)
		{
			$total = $this->getCountByYearMonth($domain,$tag,$year,$month);
			if($total)
			{
				$resultList[$month] = $total;
			}
		}
		return $resultList;
	}

	/**
	 * 返回按年月日小时的计数
	 *
	 * @param string $domain
	 * @param string $tag
	 * @param string $year
	 * @param string $month
	 * @param string $day
	 * @param string $hour
	 * @return int
	 */
	public function getCountByYearMonthDayHour($domain,$tag,$year,$month,$day,$hour)
	{
		$total = 0;
		$total = self::$redisConnection->hGet("$domain:$tag:$year:$month:$day",$hour);
		return $total;
	}

	/**
	 * 按天统计访问计数
	 *
	 * @param string $domain
	 * @param string $tag
	 * @param string $year
	 * @param string $month
	 * @return array day=>count
	 */
	public function getDayCountList($domain,$tag,$year,$month)
	{
		$resultList = array();
		$days = array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');
		foreach ($days as $day)
		{
			$total = $this->getCountByYearMonthDay($domain,$tag,$year,$month,$day);
			if($total > 0)
			{
				$resultList[$day] = $total;
			}
		}
		return $resultList;
	}
	/**
	 * 返回按年月日的总计数
	 *
	 * @param string $domain
	 * @param string $tag
	 * @param string $year
	 * @param string $month
	 * @param string $day
	 * @return int
	 */
	public function getCountByYearMonthDay($domain,$tag,$year,$month,$day)
	{
		$total = 0;
		$keys = self::$redisConnection->keys("$domain:$tag:$year:$month:$day");
		if(count($keys) < 0)
			return null;
		foreach ($keys as $item)
		{
			$total += $this->countTotalByHashKey($item);
		}
		return $total;
	}

	/**
	 * 按小时统计计数
	 *
	 * @param string $domain
	 * @param string $tag
	 * @param string $year
	 * @param string $month
	 * @param string $day
	 * @return array hour => count
	 */
	public function getHourCountList($domain,$tag,$year,$month,$day)
	{
		$resultList = array();
		$hashKey = "$domain:$tag:$year:$month:$day";
		return self::$redisConnection->hGetAll($hashKey);
	}


	public function remove($domain,$tag = '',$year = '',$month = '',$day = '')
	{

		$keySearch = $domain;
		if(!empty($tag))
			$keySearch .= ':'.$tag;
		if(!empty($year) && !empty($tag) )
			$keySearch .=':'.$year;
		if(!empty($month) && !empty($year) && !empty($tag))
			$keySearch .=':'.$month;
		if(!empty($day) && !empty($month) && !empty($year) && !empty($tag))
			$keySearch .=':'.$day;

		$keys = self::$redisConnection->keys("$keySearch*");
		if(count($keys) < 0)
			return true;
		foreach ($keys as $key)
		{
			$this->redisConnection->delete($key);
		}
	}


	/**
	 * 根据hash key 计算总数
	 *
	 * @param string $hashKey
	 * @return int
	 */
	private function countTotalByHashKey($hashKey)
	{
		$total = 0;
		$hash = self::$redisConnection->hGetAll($hashKey);
		if(count($hash)<0)
			return 0;
		foreach ($hash as $key => $value)
		{
			$total +=$value;
		}
		return $total;
	}


	/**
	 * 构建日期key
	 *
	 * @param int $timestamp
	 * @return string year:month:day
	 */
	private function getDateKey($timestamp)
	{
		$year = date('Y',$timestamp);
		$month = date('m',$timestamp);
		$day = date('d',$timestamp);
		return $year.':'.$month.':'.$day;
	}

	/**
	 * 构建HASH key
	 *
	 * @param string $domain
	 * @param string $tag
	 * @param string $timestamp
	 * @return string domain:tag:dateKey
	 */

	private function getHashKey($domain,$tag,$timestamp)
	{
		$dateKey = $this->getDateKey($timestamp);
		$hashKey = $domain.':'.$tag.':'.$dateKey;
		return $hashKey;
	}

	/**
	 * 生成 hash 中的 子 key
	 *
	 * @param int $timestamp
	 * @return string hour='03'
	 */
	private function getSubHashKey($timestamp)
	{
		$hour = date('H',$timestamp);
		return $hour;
	}

}




//$count1 = new Counter();
//$count1->incr('lolbox','crash');
////echo $count1->getCountByYear('lolbox','crash','2012');
////echo '=====';
////echo $count1->getCountByYearMonth('lolbox','crash','2012','05');
////echo '=====';
////echo $count1->getCountByYearMonthDay('lolbox','crash','2012','05','17');
////echo '=====';
////echo $count1->getCountByYearMonthDayHour('lolbox','crash','2012','05','17','03');
////echo '==04==';
////echo $count1->getCountByYearMonthDayHour('lolbox','crash','2012','05','17','04');
//print_r($count1->getHourCountList('lolbox','crash','2012','05','17'));
//print_r($count1->getDayCountList('lolbox','crash','2012','05'));

?>