ThinkPHP 6.0
===============
### 版本
PHP 7.1.16 (cli)
Composer version 1.10.10

###
运行: php think run

=============================================
manage 管理员
id         int        管理员id
name       string     管理员名称
account    string     管理员账户
password   string     管理员密码

user 用户表
id          int       用户id
openid      string    用户的唯一标识
nickname    string    用户昵称
sex         int       用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
province    string    用户个人资料填写的省份
city        string    普通用户个人资料填写的城市
country     string    国家，如中国为CN
headimgurl  string    用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。
privilege   array      用户特权信息，json 数组，如微信沃卡用户为（chinaunicom）
unionid     string     只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。

list 商品列表
id          int        id
unique      string     商品ID
thumb       string     缩略图
title       string     商品名称
price       int        商品价格
tally       string     商品标签

detail 商品详情
id          int        详情ID
unique      string      商品ID
title       string     商品名称
price       int        商品价格

figure 商品banner图
id          int        id
unique      string     商品ID
figure      string     商品banner图

describe
id          int        id
unique      int        商品ID
describe    string     商品描述信息

skuKey    商品SKU -- key
id          int        id
unique      string     商品ID
sort        int        sku排序
value       string     sku名称

skuValue  商品SKU -- value
id          int        id
sku         int        skuID
attribute   string     sku属性
inventory   int        sku库存

order  订单列表
id          int        订单ID
unique      string     商品ID
order       string     订单号
attribute   string     已选商品属性
flow        string     物流单号

address 收获地址
id          int        id
name        string     收货人姓名
phone       string     收货人手机号
address     string     收货人地址
=============================================
