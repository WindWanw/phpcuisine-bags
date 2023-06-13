<?php

namespace Phpcuisine\Bags\ResponseFactory;

class Enum
{

    const DEFAULT = 0;
    const OK = 1000;
    const C_SUCCESS = 1001;
    const R_SUCCESS = 1002;
    const U_SUCCESS = 1003;
    const D_SUCCESS = 1004;
    const UP_SUCCESS = 1005;
    const LOGIN_SUCCESS = 1006;
    const ERROR = 2000;
    const C_ERROR = 2001;
    const R_ERROR = 2002;
    const U_ERROR = 2003;
    const D_ERROR = 2004;
    const UP_ERROR = 2005;
    const LOGIN_ERROR = 2006;

    //定义状态码对应的消息体
    const MSG = [];

    //定义需要转换成default状态的状态码
    const MSG_TO_SUCCESS = [];


    //定义默认状态码对应的消息体
    const DEFAULT_MSG = [
        self::DEFAULT => "操作成功",
        self::OK => "操作成功",
        self::C_SUCCESS => "添加成功",
        self::R_SUCCESS => "查询成功",
        self::U_SUCCESS => "修改成功",
        self::D_SUCCESS => "删除成功",
        self::UP_SUCCESS => "上传成功",
        self::LOGIN_SUCCESS => "登录成功",
        self::ERROR => "操作失败",
        self::C_ERROR => "添加失败",
        self::R_ERROR => "查询失败",
        self::U_ERROR => "修改失败",
        self::D_ERROR => "删除失败",
        self::UP_ERROR => "上传失败",
        self::LOGIN_ERROR => "登录失败",
    ];

    public function getEnumFromName(string $name): int
    {

        if ($name == "DEFAULT") {
            return self::DEFAULT;
        }

        $code = constant("self::" . $name);

        return $this->getEnum($code);
    }


    /**
     * 获取状态码
     */
    public function getEnum(int $code): int
    {

        //如果状态码小于2000或者设置了转换，则返回0
        if ($code < 2000 || in_array($code, self::MSG_TO_SUCCESS)) {
            return self::DEFAULT;
        }

        return $code;
    }


    /**
     * 获取状态码对应的消息体
     */
    public function getMsg(int $code): string
    {

        $msg = self::MSG;

        if ($msg) {

            $msg_key = array_keys($msg);
            $d_msg_key = array_keys(self::DEFAULT_MSG);

            if ($intersect = array_intersect($msg_key, $d_msg_key)) {
                throw new \Exception("无法定义默认的状态码:" . implode("|", $intersect), 1);
                return false;
            }
        }

        $_msg = self::DEFAULT_MSG + $msg;
        ksort($_msg);

        return $_msg[$code];
    }
}
