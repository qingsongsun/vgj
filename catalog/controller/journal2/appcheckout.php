<?php
/**
* checkout for app
*/
class ControllerJournal2Appcheckout extends Controller
{
	private $json_data=array();
	private $json=array();
    private $error = array();

	public function index(){

		$this->json_data = json_decode(file_get_contents("php://input"), true);
        $this->session->data['payment']=$this->json_data['payment'];
        $this->session->data['user_id']=$this->json_data['user_id'];
        $this->json['switch_user']=$this->session->data['switch_user'];
		$this->response->addHeader('Content-Type:application/json');
		$this->response->setOutput(json_encode($this->json));

	}

    public function getRepo(){
        $this->json_data = json_decode(file_get_contents("php://input"), true);
        $this->session->data['address']=$this->json_data['address'];
        $this->session->data['webstore']=$this->json_data['webstore'];

        // shipping info
        $this->session->data['shipping_method']=$this->json_data['shipping_method'];

        // address info
        // if ($this->isShippingRequired()) {
            if ($this->json_data['shipping_method']=="flat.flat") {
                $this->session->data['shipping_method']=array(
                    "code"=>"flat.flat",
                    "title"=>"快递",
                    "cost"=>(int)$this->config->get('config_shipping_cost'),
                    "tax_class_id"=>"0",
                    "text"=>"￥6.00"
                    );

                if (isset($this->json_data['address_id'])) {
                    $this->load->model('account/address');
                    $address_info=$this->model_account_address->getAddress($this->json_data['address_id']);
                    $this->session->data['address_info']=$address_info;
                }else if (isset($this->json_data['address'])) {
                    $new_shipping_address=$this->json_data['address'];
                    $this->session->data['new_shipping_address']=$new_shipping_address;
                }
            }else{
                $this->session->data['shipping_method']=array(
                    "code"=>"pickup.pickup",
                    "title"=>"到店自提",
                    "cost"=>(int)0,
                    "tax_class_id"=>"0",
                    "text"=>"￥0.00"
                    );
                $address_info='到店自提';
                $this->session->data['address_info']=$address_info;
            }
            $this->json['address_info']=$address_info;
            $this->json['results']=0;
        // }
        $this->response->addHeader('Content-Type:application/json');
        $this->response->setOutput(json_encode($this->json));

    }
    // public function cart($return=false){
    //     $this->json_data = json_decode(file_get_contents("php://input"), true);

    //     $this->session->data['cart']=$this->json_data['cart_info'];


    //     $this->model->load('app/checkout');
    //     $this->json['products']=$this->model_app_checkout->getProducts();
    //     $this->json['totals']=$this->model_app_checkout->getTotals();
    //     $this->json['vouchers'] = $this->model_app_checkout->getVouchers();

    //     $this->response->addHeader('Content-Type:application/json');
    //     $this->response->setOutput(json_encode($this->json));
    // }
    // 打折，减订单总价
    public function discount(){
        $this->json_data = json_decode(file_get_contents("php://input"), true);

        if (isset($this->json_data['discount_price'])) {
            $this->session->data['discount_price']=$this->json_data['discount_price'];

            $this->json['success']=true;
        }
        $this->response->addHeader('Content-Type:application/json');
        $this->response->setOutput(json_encode($this->json));
    }

    // 使用优惠券时，调取优惠券信息
    public function coupon(){
        $this->json_data = json_decode(file_get_contents("php://input"), true);
        $this->load->model('app/coupon');

        $customer_id=$this->customer->getId();

        $filter_data=array(
            "customer_id"=>$customer_id,
            "coupon_code"=>$this->json_data['coupon_code']
            );
        if ($this->model_app_coupon->getCouponByCodeCustomerid($filter_data)) {
            $coupon_info=$this->model_app_coupon->getCouponByCodeCustomerid($filter_data);
            $this->json['coupon']=$coupon_info;
        }else{
            $this->json['coupon']=false;
        }

        $this->response->addHeader('Content-Type:application/json');
        $this->response->setOutput(json_encode($this->json));
    }

    public function renderAddress(){

        $this->json_data = json_decode(file_get_contents("php://input"), true);
        $this->load->model('app/address');
        $this->session->data['user_id']=$this->json_data['user_id'];

        if ($this->json_data['switch_user']=='0') {
            $this->customer->logout();
            $this->json['address_list']='';
            $this->json['phone_erro']=0;
        }else{
            if (preg_match("/^1[34578]\d{9}$/",$this->json_data['switch_user'])) {

                $this->json['phone_erro']=0;

                // 有账号则获取id 无则建账号获取id
                $this->session->data['customer_id']=$this->model_app_address->getCustomerId($this->json_data['switch_user']);

                // 做一把登录
                if (isset($this->session->data['customer_id'])&&$this->customer->login($this->json_data['switch_user'],'',true)) {
                    if ($this->customer->isLogged()) {
                        $this->json['login']=true;
                    }else{
                        $this->json['login']=false;
                    }
                    $this->json['address_list']=$this->model_app_address->getAddresses($this->json_data['switch_user']);
                }else{
                    $this->json['address_list']='';
                }
            }else{
                $this->json['phone_erro']=1;
                $this->json['address_list']='';
            }
        }

        $this->response->addHeader('Content-Type:application/json');
        $this->response->setOutput(json_encode($this->json));

    }

	public function confirm() {

 		$this->json_data = json_decode(file_get_contents("php://input"), true);
        $this->session->data['json_data']=$this->json_data;
        $this->load->model('app/checkout');

        $order_data = $this->model_app_checkout->getOrder();

        // address info
        $address_info=$this->session->data['address_info'];
        if (isset($this->session->data['new_shipping_address'])) {
            $new_shipping_address=$this->session->data['new_shipping_address'];
            $order_data['shipping_address_1']=$new_shipping_address;
        }else{
            $order_data   = array_replace($order_data, $this->getAddressData($address_info, '', 'shipping_'));
        }

        // shipping info
        if ($this->json_data['shipping_method']=='flat.flat') {
            $order_data['shipping_method']='快递';
            $order_data['shipping_code']=$this->session->data['shipping_method'];
        }else if ($this->session->data['shipping_method']=='pickup.pickup') {
            $order_data['shipping_method']='到店自提';
            $order_data['shipping_code']=$this->session->data['shipping_method'];
        }
        // Coupon info
        $this->load->model('app/coupon');
        $total=$this->json_data['total'];

        if (isset($this->session->data['customer_id'])) {
            $coupon_arr=array();
            $coupon_arr=json_decode(COUPON,true);

            foreach ($coupon_arr as $coupon) {
                if ($coupon['max']!='') {
                    if ((int)$coupon['min']>(int)$coupon['max']) {
                        $this->log->write('优惠券配置出错');
                        continue;
                    }
                    if ($total<=(int)$coupon['max']&&$total>=(int)$coupon['min']) {

                        $coupon_info=$this->model_app_coupon->getCouponForCustomer($coupon['coupon_id']);
                        $coupon_id=$this->model_app_coupon->dupilcateCoupon(array(
                            'name'=>$coupon_info['name'],
                            'code'=>mt_rand(100000,999999),
                            'discount'=>$coupon_info['discount'],
                            'type'=>$coupon_info['type'],
                            'total'=>$coupon_info['total'],
                            'logged'=>$coupon_info['logged'],
                            'shipping'=>$coupon_info['shipping'],
                            'date_start'=>$coupon_info['date_start'],
                            'date_end'=>$coupon_info['date_end'],
                            'uses_total'=>$coupon_info['uses_total'],
                            'uses_customer'=>$coupon_info['uses_customer'],
                            'status'=>$coupon_info['status']
                            ));
                    }
                }else{
                     if ($total>=(int)$coupon['min']) {
                        $coupon_info=$this->model_app_coupon->getCouponForCustomer($coupon['coupon_id']);
                        $coupon_id=$this->model_app_coupon->dupilcateCoupon(array(
                            'name'=>$coupon_info['name'],
                            'code'=>mt_rand(100000,999999),
                            'discount'=>$coupon_info['discount'],
                            'type'=>$coupon_info['type'],
                            'total'=>$coupon_info['total'],
                            'logged'=>$coupon_info['logged'],
                            'shipping'=>$coupon_info['shipping'],
                            'date_start'=>$coupon_info['date_start'],
                            'date_end'=>$coupon_info['date_end'],
                            'uses_total'=>$coupon_info['uses_total'],
                            'uses_customer'=>$coupon_info['uses_customer'],
                            'status'=>$coupon_info['status']
                            ));
                    }
                }
            }


            $coupon_last=$this->model_app_coupon->getCouponForCustomer($coupon_id);

            $this->load->model('account/mycoupon');

            $this->model_account_mycoupon->addCustomerCoupon(array(
                'coupon_id'=>$coupon_id,
                'customer_id'=>$this->session->data['customer_id'],
                'coupon_code'=>$coupon_last['code'],
                'coupon_description'=>$coupon_last['name']
                ));

        }


        // 使用过的优惠券
        if ($this->json_data['coupon_id']==0) {
            // 未使用优惠券

        }else{
            // 使用过的优惠券的id
            $coupon_id=$this->json_data['coupon_id'];
            $this->load->model('checkout/coupon');
            $coupon_code=$this->model_checkout_coupon->getCouponCode($this->json_data['coupon_id']);
            if (isset($this->session->data['discount_price'])) {
                $this->session->data['coupon']=$this->session->data['discount_price'];
            }
            $this->session->data['coupon']=$coupon_code;
            $coupon_info = $this->model_checkout_coupon->getCoupon($coupon_code);
            $this->model_account_mycoupon->updateCouponStatus($coupon_id);
        }

        // order totals
        $order_data['totals'] = array();
        $total                = 0;
        $taxes                = $this->cart->getTaxes();

        if (Front::$IS_OC2) {
            $this->load->model('extension/extension');
            $results = $this->model_extension_extension->getExtensions('total');
        } else {
            $this->load->model('setting/extension');
            $results = $this->model_setting_extension->getExtensions('total');
        }

        $sort_order = array();

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'].'_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'].'_status')) {
                $this->load->model('total/'.$result['code']);

                $this->{'model_total_'.$result['code']}->getTotal($order_data['totals'], $total, $taxes);
            }
        }

        $sort_order = array();

        foreach ($order_data['totals'] as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $order_data['totals']);

        $order_data['total'] = $total;


        // order products
        $order_data['products'] = array();

        $this->load->model('catalog/repository');

        foreach ($this->cart->getProducts() as $product) {

            $option_data = array();

                $option_data[] = array(
                    'product_option_id'       => $product['product_option_id'],
                    'product_option_value_id' => $product['product_option_value_id'],
                    'option_id'               => $product['option_id'],
                    'option_value_id'         => $product['option_value_id'],
                    'name'                    => $product['option_value'],
                    'value'                   => Front::$IS_OC2?$option['value']:$option['option_value'],
                    'type'                    => $product['type']
                );


            $this->json['repository_del']=true;

            $order_data['products'][] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'option'     => $option_data,
                'download'   => '',
                'quantity'   => $product['quantity'],
                'subtract'   => $product['subtract'],
                'price'      => $product['price'],
                'total'      => $this->json_data['total'],
                'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
                'reward'     => $product['reward'],
            );
        }

        // Customer info
        $this->load->model('account/customer');

        if (isset($this->session->data['customer_id'])) {
            $customer_info = $this->model_account_customer->getCustomer($this->session->data['customer_id']);

            $order_data['customer_id']       = $this->session->data['customer_id'];
            $order_data['customer_group_id'] = $customer_info['customer_group_id'];
            $order_data['firstname']         = $customer_info['firstname'];
            $order_data['lastname']          = $customer_info['lastname'];
            $order_data['email']             = $customer_info['email'];
            $order_data['telephone']         = $customer_info['telephone'];
            $order_data['fax']               = $customer_info['fax'];
        }else{
            $order_data['user_id']=$this->session->data['user_id'];
        }

        // // 为app提供的直接减少订单总价
        // if (isset($this->session->data['discount_price'])) {
        //     if ($this->session->data['discount_price']<=$order_data['total']) {
        //         $order_data['total']=$order_data['total']-$this->session->data['discount_price'];
        //     }else{
        //         $order_data['total']=0;
        //     }

        // }
        // update order
        // $this->model_app_checkout->setOrderData($order_data);
        // $this->model_app_checkout->save();
        $this->load->model('journal2/checkout');
        $this->model_journal2_checkout->setOrderData($order_data);
        $this->model_journal2_checkout->save();

        $this->load->model('account/address');
        if ($this->isLoggedIn()) {
            // save new shipping address
            if ($new_shipping_address) {
                $new_shipping_address=array(
                    'shipping_address_1'=>$new_shipping_address
                    );
                $this->model_account_address->addAddress($this->getAddressData($new_shipping_address, 'shipping_'));
            }
            $this->model_journal2_checkout->updateCustomer();
        } else {
            $this->session->data['guest'] = $this->getAddressData($order_data, 'payment_');
        }

        // 现金支付 写入历史订单 赋予一个订单状态：
        $this->load->model('checkout/order');
        if ($this->json_data['payment']=='cash') {
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], 2);
        }


        header('Content-Type: application/json');
                echo json_encode(array(
                        'total_app'=>$order_data['total'],
                        'repository_del'=>$this->json['repository_del'],
                        'order_id'=>$this->session->data['order_id'],
                        'order_data' => $order_data,
                    ));
                exit;

    }

    private function getAddressData($array, $key = '', $prefix = '') {
        $keys = array(
            'address_1',
            'address_2',
            'address_id',
            'address_format',
            'city',
            'company',
            'company_id',
            'country',
            'country_id',
            'firstname',
            'lastname',
            'method',
            'postcode',
            'tax_id',
            'zone',
            'zone_id',
        );

        $result = array();

        foreach ($keys as $k) {
            $result[$prefix.$k] = Journal2Utils::getProperty($array, $key.$k, '');
        }

        if ($result[$prefix.'country_id']) {
            $country_info = $this->model_localisation_country->getCountry($result[$prefix.'country_id']);
            if ($country_info) {
                if (!$result[$prefix.'country']) {
                    $result[$prefix.'country'] = $country_info['name'];
                }
                $result[$prefix.'address_format'] = $country_info['address_format'];
            }
        }

        if (!$result[$prefix.'zone'] && $result[$prefix.'zone_id']) {
            $zone_info = $this->model_localisation_zone->getZone($result[$prefix.'zone_id']);
            if ($zone_info) {
                $result[$prefix.'zone'] = $zone_info['name'];
            }
        }

        if (Front::$IS_OC2) {
            $result[$prefix.'custom_field'] = Journal2Utils::getProperty($array, $key.'custom_field', array());
        }
        return $result;
    }

    public function cancelOrder(){
        $this->json_data = json_decode(file_get_contents("php://input"), true);
        $this->load->model('checkout/order');
        // 订单状态变更为已取消
        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'],7);
        $this->json['success']=true;
        // 订单取消，修改优惠券的状态
        $this->load->model('account/mycoupon');
        if (isset($this->json_data['coupon_id'])&&$this->json_data['coupon_id']!=0) {
            $this->model_account_mycoupon->recoverCouponStatus($this->json_data['coupon_id']);
        }

        // 订单取消，需要删除二维码
        $qrcode_file=DIR_SYSTEM.'weipayqrcode/'.$this->session->data['out_trade_no'].'.png';
        if (unlink($qrcode_file)) {
            $this->log->write('嘿嘿,删除成功');
        }else{
            $this->log->write('2222，删除没成功');
        }
        unset($this->session->data['order_id']);
        $this->response->addHeader('Content-Type:application/json');
        $this->response->setOutput(json_encode($this->json));
    }

    // public function success(){
    //     $this->json_data = json_decode(file_get_contents("php://input"), true);
    //     $this->session->data['json_data']=$this->json_data;
    //     $this->json['success']=1;
    //     $this->response->addHeader('Content-Type:application/json');
    //     $this->response->setOutput(json_encode($this->json));
    // }

    private function isShippingRequired() {
        return $this->cart->hasShipping();
    }

    private function isLoggedIn() {
        return $this->customer->isLogged();
    }

    public function shipping($return=false){
        $this->updateShippingPrice();
    }

    public function updateShippingPrice() {

        if ($this->session->data['shipping_method']['code'] != 'flat.flat') {return;
        }
        $this->session->data['shipping_method']['cost'] = DELIVERY_JIANG_ZHE_HU;
    }
}
