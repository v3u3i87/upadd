<?php
/**
 * demo
// 	public function demo(){
// 		//引入拓展类
// 		LoadApi('Queue.class.php');
// 		$q = new Queue(800);
// 		for ($i=0;$i<1000000;$i++){
// 			$q->InQ('i:'.$i.'我是强哥'.mt_rand(888, 99999));
// 			$q->OutQ();
// 		}
// 	}
 * @author Administrator
 * 
 */
defined('UPADD_HOST') or exit();

class Queue{
	
	protected $front;//队头
	
	protected $rear;//队尾
	
	protected $queue=array('0'=>'队尾');//存储队列
	
	protected $maxsize;//最大数
	
	public function __construct($size){
		$this->initQ($size);
	}
	
	//初始化队列
	private function initQ($size){
		$this->front=0;
		$this->rear=0;
		$this->maxsize=$size;
	}
	
	//判断队空
	public function QIsEmpty(){
		return $this->front==$this->rear;
	}
	//判断队满
	public function QIsFull(){
		return ($this->front-$this->rear)==$this->maxsize;
	}
	//获取队首数据
	public function getFrontDate(){
		return $this->queue[$this->front]->getData();
	}
	//入队
	public function InQ($data){
		if($this->QIsFull()){
			echo $data.":我一来咋就满了！（队满不能入队，请等待！）<br>";
		}else {
			$this->front++;
			for($i=$this->front;$i>$this->rear;$i--){
// 				echo $data;
// 				exit;
				if(isset($this->queue[$i])){unset($this->queue[$i]);}
				$this->queue[$i]=$this->queue[$i-1];
		}
			$this->queue[$this->rear+1]=new QueueData($data);
			
			print_r($this->queue);
			echo $this->front;
			echo '入队成功！<br>';
		}
	}
	
	//出队
	public function OutQ(){
		if($this->QIsEmpty())echo "队空不能出队！<br>";
		else{
			unset($this->queue[$this->front]);
			$this->front--;
			//print_r($this->queue);
			//echo $this->front;
			echo "出队成功！<br>";
		}
	}

}//End Class Name Queue

//队列数据
class QueueData{
	//数据
	private $data;

	public function __construct($data){
		$this->data = $data;
		echo $data.":哥进队了！<br>";
	}

	public function getData(){
		return $this->data;
	}
	
	public function __destruct(){
		echo $this->data."：哥走了！<br>";
	}
	
}
