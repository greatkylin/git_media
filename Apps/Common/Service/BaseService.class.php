<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/7/30
 * Time: 16:17
 */

namespace Common\Service;


class BaseService
{
    protected $errors = array();  //保存错误信息的


    /**
     * 设置错误信息，当业务流程出现错误无法继续执行时，使用如下方法返回：
     * return $this->setError('error message');
     * @param string $message 错误信息描述
     * @param string $attribute 属性名称，默认为null，表示错误为逻辑错误，而非字段格式错误
     * @return boolean 始终返回false
     */
    public function setError($message, $attribute = null) {
        $attribute = ($attribute == null)? '_error':$attribute;
        $this->errors[$attribute][] = $message;

        // TODO记录日志

        return false;
    }

    /**
     * 取得错误信息
     * @param string $attribute 属性名称，默认为null，表示获取逻辑错误
     * @return array 返回错误信息
     */
    public function getError($attribute = null) {
        $attribute = ($attribute == null)? '_error':$attribute;
        return isset($this->errors[$attribute])? $this->errors[$attribute]:array();
    }

    /**
     * 取得第一个错误信息
     * @param null $attribute
     * @return mixed|null
     */
    public function getFirstError($attribute = null) {
        $attribute = ($attribute == null) ? '_error' : $attribute;
        $errors = isset($this->errors[$attribute]) ? $this->errors[$attribute] : reset($this->errors);
        $firstError = null;
        if (isset($errors[0])) {
            if (is_array($errors[0])) {
                foreach ($errors[0] as $error) {
                    if ($error != '') {
                        $firstError = $error;
                        break;
                    }
                }
            } else {
                $firstError = $errors[0];
            }
        }
        return $firstError;

    }

    /**
     * 取得所有错误信息
     * @return array 返回所有错误信息
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * 清空错误信息
     */
    public function clearErrors() {
        $this->errors = array();
    }

    /**
     * 是否有错误信息
     * @return boolean 返回是否有错误信息
     */
    public function hasErrors() {
        if($this->errors == null)
            return false;
        foreach ($this->errors as $key=>$errors) {
            if(count($errors) > 0)
                return true;
        }

        return false;
    }

    /**
     * 添加字段验证错误信息
     * @param array $errors 字段验证错误信息
     * @param $errors
     * @return bool
     */
    public function addErrors($errors) {
        foreach($errors as $attribute=>$error) {
            if(is_array($error)) {
                foreach($error as $e)
                    $this->errors[$attribute][]=$e;
            }
            else
                $this->errors[$attribute][]=$error;
        }

        return false;
    }

    /**
     * 获取文章的所有栏目分类，并存在redis中
     * @author xy
     * @since 2017/07/31 13:59
     * @return bool|mixed
     */
    public static function getAllColumnInfoFromRedis(){
        $columns = S('media_article_category');
        if(empty($columns)){
            return $columns = self::updateColumnInfoInRedis();
        }else{
            return $columns;
        }

    }

    /**
     * 更新redis缓存中的所有栏目分类
     * @author xy
     * @since 2017/08/22 14:00
     */
    public static function updateColumnInfoInRedis(){
        $columns = M(C('DB_ZHIYU.DB_NAME').'.'.'category', C('DB_ZHIYU.DB_PREFIX'))
            ->field('catid, cat_name, parent_id')
            ->select();
        if(!empty($columns)){
            S('media_article_category', $columns, array('expire'=>86400));
            return $columns;
        }
        return false;
    }

    /**
     * 获取后台用户信息
     * @author xy
     * @since 2017/07/25 17:05
     * @return bool|mixed
     */
    protected static function getAdminUserInfo(){
        $userInfo = session('user_info');
        if(!$userInfo){
            return false;
        }
        return $userInfo;
    }

    /**
     * 获取后台用户信息
     * @author xy
     * @since 2017/07/25 17:05
     * @return bool|mixed
     */
    protected static function getUserInfo(){
        $userInfo = session('media_web_user');
        if(!$userInfo){
            return false;
        }
        return $userInfo;
    }

    /**
     * 获取爱奇艺授权
     * @return array
     */
    public function iqiyiAuthorize ($type = 0) {
        $redis = myclass('RedisExt', 'Redis');
        $access_token = $redis->get('iqiyi_access_token' . $type);
        if (!$access_token) {
            $params = [
                'client_id' => C('iqiyi.appKey')[$type],
                'client_secret' => C('iqiyi.appSecret')[$type]
            ];
            $result = http('https://openapi.iqiyi.com/api/iqiyi/authorize', $params, 'GET');
            $res = json_decode($result);
            if ($res->code == 'A00000') {
                $redis->set('iqiyi_access_token' . $type, $res->data->access_token, $res->data->expires_in);//保存用户信息
                $return_res = [
                    'code' => 1,
                    'access_token' => $res->data->access_token,
                    'msg' => '授权成功'
                ];
            } else {
                $return_res = [
                    'code' => 0,
                    'access_token' => '',
                    'msg' => '授权失败',
                ];
            }
        } else {
            $return_res = [
                'code' => 1,
                'access_token' => $access_token,
                'msg' => '授权成功'
            ];
        }

        return $return_res;
    }


    /**
     * 爱奇艺视频状态
     * @param $access_token
     * @param $file_id
     * @return mixed
     */
    public function iqiyiFullStatus ($access_token, $file_id) {
        $status_res = http('http://openapi.iqiyi.com/api/file/fullStatus',
            [
                'access_token' => $access_token,
                'file_id' => $file_id
            ], 'GET');
        $status_res = json_decode($status_res);
        return $status_res;
    }

    /**
     * 爱奇艺视频url列表
     * @param $access_token
     * @param $file_id
     * @return mixed
     */
    public function iqiyiUrlList ($access_token, $file_id) {
        $status_res = http('http://openapi.iqiyi.com/api/file/urllist',
            [
                'access_token' => $access_token,
                'file_id' => $file_id
            ], 'GET');
        $status_res = json_decode($status_res);
        return $status_res;
    }

    /**
     * 删除爱奇艺视频
     * @param $access_token
     * @param $file_id
     * @return mixed
     */
    public function iqiyiDelete ($access_token, $file_id) {
        //delete_type0 表示删除全部，1 表示删除 file_ids 的 id
        $res = http('https://openapi.iqiyi.com/api/file/delete',
            [
                'delete_type' => 1,
                'access_token' => $access_token,
                'file_ids' => $file_id
            ], 'GET');
        $res = json_decode($res);
        return $res;
    }

    /**
     * 获取爱奇艺的视频url
     * @param $file_id
     * @param int $type
     * @return string
     */
    public function getIqiyiVideoUrl ($file_id, $type=2, $iqiyi_index = 0) {
        $auth = $this->iqiyiAuthorize($iqiyi_index);
        if ($auth['code'] == 1) {
            $status_res = $this->iqiyiFullStatus($auth['access_token'], $file_id);
            //状态：0发布中 1发布成功  2：审核失败 3上传失败
            if ($status_res->code == 'A00000') {//A00000	视频处理完成
                $status = 1;
            } elseif ($status_res->code == 'A00001') {//A00001	视频发布中
                $status = 0;
            } elseif ($status_res->code == 'A00002') {//A00002	视频审核失败
                $status = 2;
            } else {
                $status = 2;
            }

            if ($status == 1) {
                $result = $this->iqiyiUrlList($auth['access_token'], $file_id);
                if ($result->code == 'A00000') {
                    if ($result->data->mp4->$type) {
                        return $result->data->mp4->$type;
                    } else {
                        $type=1;
                        return $result->data->mp4->$type;
                    }
                }
            } else {
                M(C('DB_ZHIYU.DB_NAME') . '.' . 'video_lib', C('DB_ZHIYU.DB_PREFIX'))->where(['file_id' => $file_id])->save(['status' => $status]);
            }
        }
        return '';

    }

    /**
     * 获取爱奇艺视频的信息
     * @param $file_id
     * @return array
     */
    public function getIqiyiVideoInfo ($file_id, $iqiyi_index = 0) {
        $auth = $this->iqiyiAuthorize($iqiyi_index);
        if ($auth['code'] == 1) {
            /*$result = $this->iqiyiFullStatus($auth['access_token'], $file_id);
            //状态：0发布中 1发布成功  2：审核失败 3上传失败
            if ($result->code == 'A00000') {//A00000	视频处理完成
                $status = 1;
            } elseif ($result->code == 'A00001') {//A00001	视频发布中
                $status = 0;
            } elseif ($result->code == 'A00002') {//A00002	视频审核失败
                $status = 2;
            } else {
                $status = 2;
            }*/
            //if ($status == 1) {
            $status_res = http('http://openapi.iqiyi.com/api/file/videoListForExternal',
                [
                    'access_token' => $auth['access_token'],
                    'file_ids' => $file_id
                ], 'GET');
            $status_res = json_decode($status_res, true);
            if ($status_res['code'] == 'A00000') {
                return $status_res['data'][0];
            }
            //} else {
            //    M(C('DB_ZHIYU.DB_NAME') . '.' . 'video_lib', C('DB_ZHIYU.DB_PREFIX'))->where(['file_id' => $file_id])->save(['status' => $status]);
            //}
        }
        return [];
    }
}