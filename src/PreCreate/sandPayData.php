<?php
/**
 * Created by PhpStorm.
 * User: zhaiyujin
 * Date: 20-3-19
 * Time: 下午3:39
 */

namespace zhaiyujin\sandpay\PreCreate;

use function is_array;
use zhaiyujin\sandpay\Contracts\AbstractCompare;

class sandPayData extends AbstractCompare
{

    function setBody(Body $body)
    {

        $this->values['body'] = $body;
        return $this;
    }

    function setHead(Head $body)
    {

        $this->values['head'] = $body;
        return $this;
    }

}