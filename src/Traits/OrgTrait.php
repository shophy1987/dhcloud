<?php 

namespace DahuaCloud\Traits;

use DahuaCloud\Helper\Utils;

trait OrgTrait
{
    // 获取组织列表
    public function getOrgs($params = [])
    {
        Utils::checkArrayPositiveInt($params, 'pageNum');
        Utils::checkArrayPositiveInt($params, 'pageSize');
        return $this->post('membership/api/org/list', $params);
    }

    // 新增组织
    public function createOrg($params = [])
    {
        Utils::checkArrayEmptyStr($params, 'orgName');
        Utils::checkArrayEmptyStr($params, 'pOrgCode');
        return $this->post('membership/api/org', $params);
    }

    // 删除组织
    public function deleteOrg($params = [])
    {
        Utils::checkArrayEmptyStr($params, 'orgCode');
        return $this->post('membership/api/org/' . $params['orgCode']);
    }
}
