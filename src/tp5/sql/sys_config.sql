-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-12-20 22:00:22
-- 服务器版本： 5.7.18-log
-- PHP Version: 7.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itboye_jinpu`
--

--
-- 转存表中的数据 `common_config`
--

INSERT INTO `common_config` (`name`, `project_id`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES
('WEBSITE_TITLE', 'jigongbao', 1, '网站标题', 1, '', '网站标题前台显示标题', 1378898976, 1379235274, 1, '管理平台', 0),
( 'CONFIG_TYPE_LIST', 'jinpu', 3, '配置类型列表', 4, '', '主要用于数据解析和页面表单的生成', 1378898976, 1435743903, 1, '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举\r\n5:图片', 2),
('WEBSITE_ICP', 'jinpu', 1, '网站备案号', 1, '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', 1378900335, 1379235859, 1, '', 9),
( 'APP_VERSION', 'jinpu', 0, '程序版本', 4, '', '程序版本主版本+大改动+小改动', 1419496611, 1419496611, 1, '1.0.0', 5),
('CONFIG_GROUP_LIST', 'jinpu', 3, '配置分组', 4, ' e', '配置分组', 1379228036, 1491124301, 1, '1:基本\r\n2:内容\r\n4:系统\r\n7:支付\r\n8:邮件\r\n11:APP推送\r\n6:APP通用\r\n12:Android\r\n13:IOS  \r\n14:API配置\r\n15:短信', 1),
('LIST_ROWS', 'jinpu', 0, '后台每页记录数', 2, '', '后台数据每页显示记录数', 1379503896, 1380427745, 1, '10', 9),
( 'DATA_BACKUP_PATH', 'jinpu', 1, '数据库备份根路径', 4, '', '路径必须以 / 结尾', 1381482411, 1381482411, 1, '../data/', 5),
( 'DATA_BACKUP_PART_SIZE', 'jinpu', 0, '数据库备份卷大小', 4, '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', 1381482488, 1381729564, 1, '20971520', 7),
( 'DATA_BACKUP_COMPRESS', 'jinpu', 4, '数据库备份文件是否启用压缩', 4, '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', 1381713345, 1381729544, 1, '1', 9),
('DATA_BACKUP_COMPRESS_LEVEL', 'jinpu', 4, '数据库备份文件压缩级别', 4, '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', 1381713408, 1381713408, 1, '9', 10),
( 'DEVELOP_MODE', 'jinpu', 0, '开发模式', 4, '', '标识是否为开发环境下\r\n', 1419408635, 1419408635, 1, '1', 1),
( 'WEBSITE_OWNER', 'jinpu', 1, '网站拥有者', 1, '', '当前网站拥有者或是开发者', 1419415563, 1419415563, 1, '管理平台', 1),
( 'DEFAULT_THEME', 'jinpu', 4, '默认主题样式', 0, '', '默认主题样式', 1419415629, 1419415629, 1, 'default', 0),
( 'ADMIN_ALLOW_IP', 'jinpu', 2, '后台允许访问IP', 4, '', '多个用逗号分隔，如果不配置表示不限制IP访问', 1387165454, 1387165553, 1, '', 12),
( 'UCENTER_PLATFORM', 'jinpu', 1, '管理平台名称', 1, '', '', 1420006808, 1420006808, 1, '管理平台', 1),
( 'DEFAULT_SKIN', 'jinpu', 8, '默认皮肤', 4, '0:simplex\r\n1:flatly\r\n2:darkly\r\n3:cosmo\r\n4:paper\r\n5:slate\r\n6:superhero\r\n7:united\r\n8:yeti\r\n9:spruce\r\n10:readable\r\n11:cyborg\r\n12:cerulean', '系统皮肤', 1420435487, 1481876374, 1, '1', 3),
( 'ALLOW_VISIT', 'jinpu', 3, '允许访问方法', 4, '', '一般通用访问方法添加到此', 1421148508, 1421148508, 1, '', 0),
( 'DENY_VISIT', 'jinpu', 3, '不可访问方法', 4, '', '非超级管理员不可访问方法', 1421148575, 1421148575, 1, '', 0),
( 'JUHE_API', 'jinpu', 3, '聚合API', 4, '', '聚合使用', 1441596571, 1444802527, 1, 'EXPRESS_APPKEY:56104543f69b2498b3e8041d50ad6a71\r\nEXPRESS_SENDURL:http://v.juhe.cn/exp/index\r\nMSG_APPKEY:d349133a9ea2f7a12ce0a75abef0bb2d\r\nMSG_TPL_ID:5599\r\nMSG_SENDURL:http://v.juhe.cn/sms/send', 0),
( 'GREEN_PICTURE_TIME', 'jinpu', 0, '绿网图片检测数量当日次数', 2, '', '绿网图片检测数量当日次数', 1379503896, 1380427745, 1, '20', 9),
( 'HOOKS_TYPE', 'jinpu', 3, '钩子类型', 1, '', '类型 1-用于扩展显示内容（在页面上），2-用于扩展业务处理（在代码里）', 1435716893, 1448087877, 1, '1:视图\r\n2:控制器', 0),
( 'ADMIN_LOGO', 'jinpu', 5, '后台顶部LOGO', 1, '', '后台做上角顶部LOGO，150px*40px,底色与顶部一致', 1435743870, 1435744002, 1, '', 0),
( 'GLOBAL_QQ', 'jinpu', 0, '商城全站QQ客服配置1号', 1, '', '商城用的客服QQ.', 1446185695, 1446185695, 1, '', 0),
( 'ALIPAY_API', 'jinpu', 3, '支付宝API', 7, '', '', 1441857154, 1478335032, 1, 'ALIPAY_PARTNER:2088211347135986\r\nALIPAY_SELLER_EMAIL:hzboye@163.com\r\nALIPAY_KEY:2v902c71eki6839hkev14kfad4836kdu\r\nALIPAY_NOTIFY_URL:http://api.guannan.itboye.com/index.php/Alipay/notify\r\nALIPAY_RETURN_URL:http://localhost/github/itboye20150817/index.php/Shop/Orders/paysucc', 0),
( 'smtp_password', 'jinpu', 1, '发送方邮件密码', 8, '', '', 1488351894, 1488353622, 1, 'sunsunSUNSUN2017', 0),
( 'CUSTOMER_PHONE', 'jinpu', 3, '客服电话', 13, '', '手机端在线电话', 1444982224, 1476771344, 1, 'tel:400-863-9156\r\nname: XXX', 0),
( 'FILTRATION_KEYWORDS', 'jinpu', 2, '过滤关键词', 1, '', '过滤关键词', 1459935711, 1460167342, 1, '', 0),
( 'IOS_VERSION', 'jinpu', 1, 'ios版本', 6, '', 'app版本', 1459935711, 1478329024, 1, '1.0.0', 0),
( 'ANDROID_VERSION', 'jinpu', 0, 'android版本', 12, '', 'app版本', 1459935711, 1478328548, 1, '1000', 0),
( 'APP_DOWNLOAD_URL', 'jinpu', 2, 'APP下载地址', 13, '', '下载地址', 1459935711, 1478328433, 1, 'https://apidev.itboye.com/appdownload.php', 0),
( 'IOS_UPDATE_LOG', 'jinpu', 1, 'ios更新日志', 6, '', '版本更新日志', 1459935711, 1459935711, 1, 'ios_v1.0.0<br/>性能优化', 0),
( 'ANDROID_UPDATE_LOG', 'jinpu', 2, 'android更新日志', 12, '', '版本更新日志', 1459935711, 1478328032, 1, '最新版本 1.0.0\r\n1. 性能优化      \r\n2. bug修复      \r\n     ', 0),
( 'IOS_PAY_TYPE', 'jinpu', 3, 'IOS支付方式', 6, '', 'IOS支付方式', 1466133211, 1466133211, 1, '1:支付宝支付\r\n2:微信支付\r\n3:余额支付', 0),
( 'ANDROID_PAY_TYPE', 'jinpu', 3, '安卓支付方式', 12, '', '', 1466133249, 1466133249, 1, '1:支付宝支付\r\n2:微信支付\r\n3:余额支付', 0),
( 'BY_SKIN', 'jinpu', 4, 'BY_SKIN皮肤', 4, '0:skin-blue 1:skin-blue-light 2:skin-black 3:skin-black-light 4:skin-green 5:skin-green-light 6:skin-red 7:skin-red-light 8:skin-yellow 9:skin-yellow-light 10:skin-purple 11:skin-purple-light', '皮肤', 1474339779, 1474339779, 1, '0', 0),
( 'smtp_username', 'jinpu', 1, '发送邮件地址', 8, '', '系统用此邮箱作为发送方', 1488351856, 1488351856, 1, 'sunsun@email.8raw.com', 0),
( 'smtp_port', 'jinpu', 1, 'smtp端口', 8, '', '', 1488353646, 1488353646, 1, '80', 0),
( 'smtp_send_email', 'jinpu', 1, 'smtp发送方邮件完整地址', 8, '', 'smtp发送方邮件完整地址（例如: postmaster@itboye.com）', 1488355460, 1488355521, 1, 'sunsun@email.8raw.com', 0),
( 'smtp_sender_name', 'jinpu', 1, '发送方称谓', 8, '', '发送方称谓', 1488355502, 1488355566, 1, '股份有限公司', 0),
( 'smtp_host', 'jinpu', 1, '邮件服务器地址', 8, '', '邮件服务器的地址', 1488437581, 1488437581, 1, 'smtpdm.aliyun.com', 0),
( 'login_session_expire_time', 'jinpu', 0, '系统通用会话过期时间', 1, '', '', 1489212177, 1489212177, 1, '7200', 0),
( 'admin_email', 'jinpu', 1, '系统管理员邮箱', 4, '', '系统管理员邮箱', 1491449513, 1491449513, 1, 'hebiduhebi@126.com', 0),
( 'sys_default_password', 'jinpu', 1, '系统中用户默认密码', 1, '', '系统中用户默认密码,长度控制8-24最佳', 1492066573, 1492066761, 1, 'it12345678', 0),
( 'umeng_ios_jinpu', 'jinpu', 3, '金普医院（ios）', 11, '', '金普医院（ios）', 1511579884, 1511588520, 1, 'alias_type:sunsun_lingshou\r\ndevice_type:ios\r\nappkey:59b1ffd3c62dca2beb000043\r\nsecret:vq5tljwcwy4wikfhdqxk98uz4bozjgcr\r\nproduction_mode:true', 0),
( 'umeng_jigongbao_ios', 'jinpu', 3, '记工宝(IOS)', 11, '', '记工宝(IOS)', 0, 0, 0, '', 0),
( 'umeng_jigongbao_android', 'jinpu', 3, '记工宝(android)', 11, '', '记工宝（android)', 0, 0, 0, '', 0),
( 'umeng_android_jinpu', 'jinpu', 3, '金普医院（安卓）', 11, '', '金普医院（安卓）', 1511579928, 1511579928, 1, '', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
