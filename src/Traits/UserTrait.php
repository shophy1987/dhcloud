<?php 

namespace DahuaCloud\Traits;

use DahuaCloud\Helper\Utils;

trait UserTrait
{
    // 根据ID查询用户或管理员详情
    public function getUserInfo($params = [])
    {
        Utils::checkArrayEmptyStr($params, 'userId');
        return $this->get('membership/api/user/getUserInfo/' . $params['userId']);
    }

    // 查询用户或管理员信息
    public function getUsers($params = [])
    {
        Utils::checkArrayEmptyStr($params, 'orgCode');
        Utils::checkArrayPositiveInt($params, 'pageSize');
        Utils::checkArrayPositiveInt($params, 'pageNum');
        Utils::checkArrayPositiveInt($params, 'accountType');
        return $this->get('membership/api/user/page', $params);
    }

    // 启用用户或管理员账号
    public function enableUser($params = [])
    {
        Utils::checkArrayPositiveInt($params, 'id');
        Utils::checkArrayPositiveInt($params, 'isEnable');
        return $this->post('membership/api/user/setIsEnable', $params);
    }

    // 删除用户或管理员
	public function deleteUser($params = [])
    {
        Utils::checkArrayEmptyStr($params, 'userId');
        return $this->post('membership/api/user/delete/' . $params['userId']);
    }

    // 根据手机号查询用户或管理员详情
    public function getUser($params = [])
    {
        Utils::checkArrayEmptyStr($params, 'telephone');
        return $this->get('membership/api/user/getUser/' . $params['telephone']);
    }

    // 新增用户或管理员
    public function createUser($params = [])
    {
        Utils::checkArrayEmptyStr($params, 'username');
        Utils::checkArrayEmptyStr($params, 'name');
        Utils::checkArrayEmptyStr($params, 'orgCode');
        Utils::checkArrayEmptyStr($params, 'telephone');
        Utils::checkArrayPositiveInt($params, 'sex');
        Utils::checkEmptyStrArray($params, 'positionIds');
        Utils::checkArrayPositiveInt($params, 'accountType');
        return $this->post('membership/api/user/add', $params);
    }

    // 新增用户或管理员
    public function updateUser($params = [])
    {
        Utils::checkArrayPositiveInt($params, 'id');
        return $this->post('membership/api/user/update', $params);
    }

    // 查询所有角色信息
    public function getPositions()
    {
        return $this->get('membership/api/user/position/getCurList');
    }

    // 修改用户或管理员角色权限
    public function updateUserPostion($params = [])
    {
        Utils::checkArrayPositiveInt($params, 'id');
        Utils::checkPositiveIntArray($params, 'positionIds');
        return $this->post('membership/api/user/postion', $params);
    }

    public function getAuthCode($params = []) 
    {
        Utils::checkArrayEmptyStr($params, 'telephone');
        return $this->post('auth/api/authCode', $params);
    }
}
