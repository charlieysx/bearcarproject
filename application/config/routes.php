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


//===========================================admin-后台管理-接口===================================

//添加管理员
// $route['sysa/register']['post'] = 'admin/admin/register';
//登录
$route['sysa/login']['post'] = 'admin/admin/login';

//发布资讯
$route['sysa/news/publish']['post'] = 'admin/news/publish';
//获取资讯列表
$route['sysa/news/list']['get'] = 'admin/news/get_news_list';
//删除文章
$route['sysa/news/delete']['post'] = 'admin/news/delete';

//获取七牛上传图片token
$route['sysa/qiniu/token']['get'] = 'admin/qiniu/get_qiniu_token';

//查询与我相关的二手车列表
$route['sysa/mycar/list']['get'] = 'admin/mycar/get_car_list';
//下架与我相关的二手车
$route['sysa/mycar/under']['post'] = 'admin/mycar/under';

//===========================================admin-后台管理-接口-结束===============================






//===========================================user-前端-接口===================================

//注册
$route['u/register']['post'] = 'user/user/register';
//登录
$route['u/login']['post'] = 'user/user/login';
//批量添加用户
//  $route['u/add']['post'] = 'user/user/add';

//获取资讯列表
$route['u/news/list']['get'] = 'user/news/get_news_list';
//获取热门资讯
$route['u/news/list/hot']['get'] = 'user/news/get_hot_news_list';
//获取资讯详情
$route['u/news/detail']['get'] = 'user/news/get_news_info';

//发布二手车
$route['u/sellcar']['post'] = 'user/sellcar/sell_car';
//获取发布二手车数据
$route['u/sellcar/sellinfo']['get'] = 'user/sellcar/get_sell_info';
//获取预约检测二手车时间
$route['u/sellcar/checktime']['get'] = 'user/sellcar/get_check_time';

//查询我的二手车列表
$route['u/mycar/list']['get'] = 'user/mycar/get_my_car';
//下架我的二手车
$route['u/mycar/under']['post'] = 'user/mycar/under';

//获取七牛上传图片token
$route['u/qiniu/token']['get'] = 'user/qiniu/get_qiniu_token';

//===========================================user-前端-接口-结束===============================






//===========================================common-共用-接口===================================

//获取车brand
$route['c/carinfo/brand']['get'] = 'common/carinfo/get_brand';
//获取车series
$route['c/carinfo/series']['get'] = 'common/carinfo/get_series_by_brand_id';
//获取车model
$route['c/carinfo/model']['get'] = 'common/carinfo/get_model_by_series_id';
//获取按首字母排序的车brand
$route['c/carinfo/brand/sort']['get'] = 'common/carinfo/get_brand_sort';
//获取热门的车brand
$route['c/carinfo/brand/hot']['get'] = 'common/carinfo/get_hot_brand';
//获取热门的车series
$route['c/carinfo/series/hot']['get'] = 'common/carinfo/get_hot_series';

//获取省份
$route['c/city/province']['get'] = 'common/city/get_province';
//获取城市
$route['c/city']['get'] = 'common/city/get_city';
//获取省份对应的城市
$route['c/city/by_province']['get'] = 'common/city/get_city_by_province_id';
//获取城市对应的地区
$route['c/city/district/by_city']['get'] = 'common/city/get_district_by_city_id';
//获取城市关联的省份、城市列表、地区列表信息
$route['c/city/info']['get'] = 'common/city/get_info_by_city';
//获取按首字母排序的城市
$route['c/city/sort']['get'] = 'common/city/get_city_sort';
//获取热门的城市
$route['c/city/hot']['get'] = 'common/city/get_hot_city';

//===========================================common-共用-接口-结束===============================