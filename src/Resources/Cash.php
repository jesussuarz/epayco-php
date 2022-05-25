<?php

namespace Epayco\Resources;

use Epayco\Resource;
use Epayco\Exceptions\ErrorException;

/**
 * Cash payment methods
 */
class Cash extends Resource
{
    /**
     * Return data payment cash
     * @param  String $type method payment
     * @param  String $options data transaction
     * @return object
     */
    public function create($type = null)
    {

        $methods_payment = $this->request(
            "GET",
            "/payment/cash/entities",
            $api_key = $this->epayco->api_key,
            null,
            $private_key = $this->epayco->private_key,
            $test = $this->epayco->test,
            $switch = false,
            $lang = $this->epayco->lang,
            $cash = false,
            false,
            true
        );
        if(!isset($methods_payment->data) && !is_array($methods_payment->data)){
            throw new ErrorException($this->epayco->lang, 106);
        }
        $options = array_map(function($item){
            return strtolower($item->name);
        }, $methods_payment->data);

        if(!in_array(strtolower($type),  $options)){
            throw new ErrorException($this->epayco->lang, 109);

        }
        
        return $this->request(
                "POST",
                "/restpagos/v2/efectivo/{$type}",
                $api_key = $this->epayco->api_key,
                $options,
                $private_key = $this->epayco->private_key,
                $test = $this->epayco->test,
                $switch = true,
                $lang = $this->epayco->lang,
                $cash = true
        );
    }

    /**
     * Return data transaction
     * @param  String $uid id transaction
     * @return object
     */
    public function transaction($uid = null)
    {
        return $this->request(
                "GET",
                "/restpagos/transaction/response.json?ref_payco=" . $uid . "&public_key=" . $this->epayco->api_key,
                $api_key = $this->epayco->api_key,
                $uid,
                $private_key = $this->epayco->private_key,
                $test = $this->epayco->test,
                $switch = true,
                $lang = $this->epayco->lang
        );
    }
}
