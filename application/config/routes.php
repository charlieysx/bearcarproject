<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = 'notfound';
$route['translate_uri_dashes'] = FALSE;

$route['test'] = 'common/Test/test';


//===========================================admin-后台管理-接口===================================

//添加管理员
// $route['sysa/register']['post'] = 'admin/admin/register';
//登录
$route['sysa/login']['post'] = 'admin/Admin/login';

//发布资讯
$route['sysa/news/publish']['post'] = 'admin/News/publish';
//获取资讯列表
$route['sysa/news/list']['get'] = 'admin/News/get_news_list';
//获取资讯详情
$route['sysa/news/detail']['get'] = 'admin/News/get_news_info';
//删除文章
$route['sysa/news/delete']['post'] = 'admin/News/delete';

//获取七牛上传图片token
$route['sysa/qiniu/token']['get'] = 'admin/Qiniu/get_qiniu_token';

//查询与我相关的二手车列表
$route['sysa/mycar/list']['get'] = 'admin/MyCar/get_car_list';
//下架与我相关的二手车
$route['sysa/mycar/under']['post'] = 'admin/MyCar/under';
//预约检测二手车
$route['sysa/mycar/ordercheck']['post'] = 'admin/MyCar/order_check';
//获取二手车信息
$route['sysa/mycar/getinfo']['get'] = 'admin/MyCar/get_mycar_info';

//获取检测完成步骤
$route['sysa/fillcar/getfillstep']['get'] = 'admin/FillCarInfo/get_fill_step';
//获取上牌年月列表
$route['sysa/fillcar/getym']['get'] = 'admin/FillCarInfo/get_year_month';
//获取检测中的二手车的基本信息(客户填写的)
$route['sysa/fillcar/getfillcarinfo']['get'] = 'admin/FillCarInfo/get_fill_car_info';
//提交检测信息-第一步
$route['sysa/fillcar/first']['post'] = 'admin/FillCarInfo/fill_car_first_step';
//提交检测信息-第二步
$route['sysa/fillcar/second']['post'] = 'admin/FillCarInfo/fill_car_second_step';
//提交检测信息-第三步
$route['sysa/fillcar/third']['post'] = 'admin/FillCarInfo/fill_car_third_step';

//===========================================admin-后台管理-接口-结束===============================






//===========================================user-前端-接口===================================

//注册
$route['u/register']['post'] = 'user/User/register';
//登录
$route['u/login']['post'] = 'user/User/login';
//批量添加用户
//  $route['u/add']['post'] = 'user/User/add';

//获取资讯列表
$route['u/news/list']['get'] = 'user/News/get_news_list';
//获取热门资讯
$route['u/news/list/hot']['get'] = 'user/News/get_hot_news_list';
//获取资讯详情
$route['u/news/detail']['get'] = 'user/News/get_news_info';

//发布二手车
$route['u/sellcar']['post'] = 'user/SellCar/sell_car';
//获取发布二手车数据
$route['u/sellcar/sellinfo']['get'] = 'user/SellCar/get_sell_info';
//获取预约检测二手车时间
$route['u/sellcar/checktime']['get'] = 'user/SellCar/get_check_time';

//查询我的二手车列表
$route['u/mycar/list']['get'] = 'user/MyCar/get_my_car';
//下架我的二手车
$route['u/mycar/under']['post'] = 'user/MyCar/under';

//获取七牛上传图片token
$route['u/qiniu/token']['get'] = 'user/Qiniu/get_qiniu_token';

//===========================================user-前端-接口-结束===============================






//===========================================common-共用-接口===================================

//获取车brand
$route['c/carinfo/brand']['get'] = 'common/CarInfo/get_brand';
//获取车series
$route['c/carinfo/series']['get'] = 'common/CarInfo/get_series_by_brand_id';
//获取车model
$route['c/carinfo/model']['get'] = 'common/CarInfo/get_model_by_series_id';
//获取按首字母排序的车brand
$route['c/carinfo/brand/sort']['get'] = 'common/CarInfo/get_brand_sort';
//获取热门的车brand
$route['c/carinfo/brand/hot']['get'] = 'common/CarInfo/get_hot_brand';
//获取热门的车series
$route['c/carinfo/series/hot']['get'] = 'common/CarInfo/get_hot_series';

//获取省份
$route['c/city/province']['get'] = 'common/City/get_province';
//获取城市
$route['c/city']['get'] = 'common/City/get_city';
//获取省份对应的城市
$route['c/city/by_province']['get'] = 'common/City/get_city_by_province_id';
//获取城市对应的地区
$route['c/city/district/by_city']['get'] = 'common/City/get_district_by_city_id';
//获取城市关联的省份、城市列表、地区列表信息
$route['c/city/info']['get'] = 'common/City/get_info_by_city';
//获取按首字母排序的城市
$route['c/city/sort']['get'] = 'common/City/get_city_sort';
//获取热门的城市
$route['c/city/hot']['get'] = 'common/City/get_hot_city';

//===========================================common-共用-接口-结束===============================