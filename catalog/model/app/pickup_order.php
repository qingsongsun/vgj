<?php
/**
* 用于用户自提
*/
class ModelAppPickupOrder extends Model
{
	/**
	 * 用于生成二维码
	 * @param  [type] $order_id   [description]
	 * @param  [type] $token_code [description]
	 * @return [type]             [description]
	 */
	private function getQrcode($order_id,$token_code){

		include DIR_SYSTEM."pub/phpqrcode/phpqrcode.php";//引入PHP QR库文件

		$value=HTTPS_SERVER."index.php?route=app/pickup/getQrcodeInfo&order_id=".$order_id."&token_code=".$token_code;
		$errorCorrectionLevel = "L";
		$matrixPointSize = "9";
		$margin='2';
		$outfile=DIR_APPLICATION.'view/qrcode/pickup/'.$order_id.'.png';
		$saveandprint=true;

		if (!file_exists($outfile)) {
			QRcode::png($value, $outfile, $errorCorrectionLevel, $matrixPointSize,true);
		}else{		//二维码存在的话则删除然后新建
			unlink($outfile);
			QRcode::png($value, $outfile, $errorCorrectionLevel, $matrixPointSize,true);
			// return false;
		}
	}

	/**
	 * 生成token_code，生成二维码
	 * @param array $data [description]
	 */
	public function addTokenCode($data=array()){
		/**
		 * 判断是否已经有记录了
		 */
		$order_query=$this->db->query("SELECT token_code FROM ".DB_PREFIX."order_pickup WHERE order_id='".(int)$data['order_id']."'");

		/**
		 * 生成二维码
		 * 注意：由于checknotifyxml函数会被调用多次，不能用该函数传来的参数
		 */

		if ($order_query->num_rows>0 ) {
			/**
			 * 已经有记录了，不修改数据库，返回token_code
			 */
			$token_code=$order_query->row['token_code'];

		}else{

			$this->event->trigger('pre.order_pickup.add', $data);

			$token_code=$data['token_code'];

			$this->db->query("INSERT INTO ".DB_PREFIX."order_pickup SET order_id='".(int)$data['order_id']."' ,token_code='".$this->db->escape($token_code)."'");

			$this->event->trigger('post.order_pickup.add', $order_id);
		}

		$this->getQrcode($data['order_id'],$token_code);


	}

	public function getTokenCode($order_id){
		$query=$this->db->query("SELECT token_code FROM ".DB_PREFIX."order_pickup WHERE order_id='".(int)$order_id."'");
		if ($query->num_rows>0) {
			return $query->row['token_code'];
		}else{
			return false;
		}
	}
}