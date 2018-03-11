#### 毕业设计-二手车数据接口
---

> ROOT_API: 你的项目地址/index.php

#### admin-后台管理-接口
|接口名称|接口地址|请求方式|
| :- | -: | -: |
| 登录 | sysa/login | post |
| 发布资讯 | sysa/news/publish | post |
| 获取资讯列表 | sysa/news/list | get |
| 获取资讯详情 | sysa/news/detail | get |
| 删除文章 | sysa/news/delete | post |
| 获取七牛上传图片token | sysa/qiniu/token | get |
| 查询与我相关的二手车列表 | sysa/mycar/list | get |
| 下架与我相关的二手车 | sysa/mycar/under | post |
| 预约检测二手车 | sysa/mycar/ordercheck | post |
| 获取检测完成步骤 | sysa/fillcar/getfillstep | get |
| 获取上牌年月列表 | sysa/fillcar/getym | get |
| 获取检测中的二手车的基本信息(客户填写的) | ysa/fillcar/getfillcarinfo | get |
| 提交检测信息-第一步 | sysa/fillcar/first | post |
| 提交检测信息-第二步 | sysa/fillcar/second | post |
| 提交检测信息-第三步 | sysa/fillcar/third | post |
| 获取用户列表 | sysa/table/user | get |
| 获取管理员列表 | sysa/table/admin | get |
| 添加banner | sysa/banner/add | post |
| 下架banner | sysa/banner/under | post |
| 编辑banner | sysa/banner/edit | post |
| 获取banner列表 | sysa/banner/list | get |
| 获取统计数据 | sysa/statistics | get |
#### user-前端-接口
|接口名称|接口地址|请求方式|
| :- | -: | -: |
| 注册 | u/register | post |
| 登录 | u/login | post |
| 获取资讯列表 | u/news/list | get |
| 获取热门资讯 | u/news/list/hot | get |
| 获取资讯详情 | u/news/detail | get |
| 发布二手车 | u/sellcar | post |
| 获取发布二手车数据 | u/sellcar/sellinfo | get |
| 获取预约检测二手车时间 | u/sellcar/checktime | get |
| 查询我的二手车列表 | u/mycar/list | get |
| 下架我的二手车 | u/mycar/under | post |
| 获取七牛上传图片token | u/qiniu/token | get |
| 用户预约看车 | u/car/ordercar | post |
#### common-共用-接口
|接口名称|接口地址|请求方式|
| :- | -: | -: |
| 获取车brand | c/carinfo/brand | get |
| 获取车series | c/carinfo/series | get |
| 获取车model | c/carinfo/model | get |
| 获取按首字母排序的车brand | c/carinfo/brand/sort | get |
| 获取热门的车brand | c/carinfo/brand/hot | get |
| 获取热门的车series | c/carinfo/series/hot | get |
| 获取省份 | c/city/province | get |
| 获取城市 | c/city | get |
| 获取省份对应的城市 | c/city/by_province | get |
| 获取城市对应的地区 | c/city/district/by_city | get |
| 获取城市关联的省份、城市列表、地区列表信息 | c/city/info | get |
| 获取按首字母排序的城市 | c/city/sort | get |
| 获取热门的城市 | c/city/hot | get |
| 获取二手车信息 | c/car/getinfo | get |
| 获取车列表 | c/car/list | post |
| 获取猜你喜欢的车列表 | c/car/likelist | post |
| 获取数据库中车的总数量 | c/car/count | get |
| 获取banner | c/banner | get |


---
##### sql文件：该项目sql目录下
* bearcar.sql : 表
* bearcar-data.sql : 表+数据
##### 数据库配置
> application/config/database.php文件
##### 七牛上传图片配置
> application/models/common/Qiniu_model.php文件


### 说明
##### 毕业设计，开源提供参考，不可用于任何商业用途
