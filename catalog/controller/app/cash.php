<?php
/**
* app cash checkout
* 现金支付，写入历史订单
*
*/
class ControllerAppCash extends Controller
{
	private $json_data=array();
	private $json=array();

    /**
     * [index description]
     * @param order_id shipping_method user_id
     * @return [type] [description]
     */
    public function index(){
      $this->json_data=json_decode(file_get_contents("php://input"),true);
      $this->session->data['order_id']=$this->json_data['order_id'];
      $this->load->model('checkout/order');
      $this->load->model('account/order');
      $this->load->model('payment/weipay');

      $this->log->write($this->json_data);
      $this->log->write('看看发过来的东西');

      $cash_method=array(
        'cashPayment'=>'现金支付',
        'bankCard'=>'银行卡支付',
        'weiPayment'=>'微信支付',
        'aliPayment'=>'支付宝支付'
        );

      $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 2);
      $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
      if ($this->json_data['shipping_method']=='pickup.pickup') {
            /**
             * app和小程序到店自提下单，状态设置为已发货
             * @var string
             */
            $order_status_id="3";
            $this->load->model('catalog/repository');
            foreach ($this->cart->getProducts() as $product) {
               if (isset($this->json_data['shipping_method'])&&$this->json_data['shipping_method']=='pickup.pickup') {

                foreach ($product['option'] as $option) {
                    $product['product_option_value_id']=$option['product_option_value_id'];
                }
                $filter_repository_data=array(
                    'order_product_id'=>$this->model_catalog_repository->getOrderProductId(array(
                        'product_id'=>$product['product_id'],
                        'order_id'=>$this->json_data['order_id']
                        )),
                    'product_option_value_id'=>$product['product_option_value_id'],
                    'product_id'=> $product['product_id'],
                    'repository_id'=>$this->session->data['repository_id'],
                    'quantity'=>$product['quantity'],
                    'user_id'=>$this->json_data['user_id']
                    );
                $this->model_catalog_repository->delRepository_pd_order($filter_repository_data);
                $this->model_catalog_repository->addOrderProductRepository($filter_repository_data);
                $this->json['repository_del']=true;

            }
        }
    }else if ($this->json_data['shipping_method']=='flat.flat') {
        $order_status_id='15';
    }
    $this->load->model('checkout/order');
    if (isset($order_status_id)) {
        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'],$order_status_id,$cash_method[$this->json_data['cash_method']]);
        unset($this->session->data['order_id']);
    }

    $this->response->addHeader('Content-Type:application/json');
    $this->response->setOutput(json_encode($this->json));
}
    public function cancelOrder(){
        $this->json_data = json_decode(file_get_contents("php://input"), true);

        $this->load->model('checkout/order');
            // 订单状态变更为已取消
        $this->model_checkout_order->addOrderHistory($this->json_data['order_id'],7);

        $this->load->model('account/mycoupon');
        if (isset($this->json_data['coupon_id'])&&$this->json_data['coupon_id']!=0) {
            $this->model_account_mycoupon->recoverCouponStatus($this->json_data['coupon_id']);
        }

        unset($this->session->data['order_id']);
        $this->response->addHeader('Content-Type:application/json');
        $this->response->setOutput(json_encode($this->json));
    }
}