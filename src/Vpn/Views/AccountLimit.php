<?php

class Vpn_Views_AccountLimit extends Pluf_Views
{

    /**
     * Returns the limitation of given account determined by $accountId and given key by $key.
     * Returns null if such limiation does not exist.
     *
     * @param string $key
     * @param integer $accountId
     *            id of the vpn-account
     * @return Vpn_AccountLimit|NULL
     */
    public static function getLimitByKey($key, $itemId)
    {
        $sql = new Pluf_SQL('`key`=%s AND `account_id`=%s', array(
            $key,
            $itemId
        ));
        $str = $sql->gen();
        $limit = Pluf::factory('Vpn_AccountLimit')->getOne($str);
        return $limit;
    }

    /**
     * Extract Key of the limitation from $match and returns related AccountLimit
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @throws Pluf_Exception_DoesNotExist if Id is given and limitation with given id does not exist or is not blong to given account
     * @return NULL|Vpn_AccountLimit
     */
    public static function getByKey($request, $match)
    {
        $account = Vpn_Util::extractAccountOr404($request, $match);
        $match['parentId'] = $account->id;
        if (! isset($match['modelKey'])) {
            throw new Pluf_Exception_BadRequest('The modelKey is not set');
        }
        $limit = self::getLimitByKey($match['modelKey'], $match['parentId']);
        if ($limit === null) {
            throw new Pluf_HTTP_Error404('Object not found (Vpn_AccountLimit,' . $match['modelKey'] . ')');
        }
        return $limit;
    }

    public function updateByKey($request, $match)
    {
        $limit = self::getByKey($request, $match);
        $match['modelId'] = $limit->id;
        $match['parentId'] = $limit->account_id;
        $p = array(
            'parent' => 'Vpn_Account',
            'parentKey' => 'account_id',
            'model' => 'Vpn_AccountLimit'
        );
        return $this->updateManyToOne($request, $match, $p);
    }

    public function create($request, $match, $param)
    {
        $account = Vpn_Util::extractAccountOr404($request, $match);
        $match['parentId'] = $account->id;
        $myParams = array_merge(array(
            'parent' => 'Vpn_Account',
            'parentKey' => 'account_id',
            'model' => 'Vpn_AccountLimit'
        ), $param);
        return $this->createManyToOne($request, $match, $myParams);
    }

    public function createOrUpdate($request, $match, $param)
    {
        $account = Vpn_Util::extractAccountOr404($request, $match);
        $limit = Vpn_AccountLimit::getLimit($request->REQUEST['key'], $account->id);

        $match['parentId'] = $account->id;
        $myParams = array_merge(array(
            'parent' => 'Vpn_Account',
            'parentKey' => 'account_id',
            'model' => 'Vpn_AccountLimit'
        ), $param);

        if (! $limit) {
            return $this->createManyToOne($request, $match, $myParams);
        }
        $match['modelId'] = $limit->id;
        return $this->updateManyToOne($request, $match, $myParams);
    }

    public function get($request, $match, $param)
    {
        $account = Vpn_Util::extractAccountOr404($request, $match);
        $match['parentId'] = $account->id;
        $myParams = array_merge(array(
            'parent' => 'Vpn_Account',
            'parentKey' => 'account_id',
            'model' => 'Vpn_AccountLimit'
        ), $param);
        return $this->getManyToOne($request, $match, $myParams);
    }

    public function find($request, $match, $param)
    {
        $account = Vpn_Util::extractAccountOr404($request, $match);
        $match['parentId'] = $account->id;
        $myParams = array_merge(array(
            'parent' => 'Vpn_Account',
            'parentKey' => 'account_id',
            'model' => 'Vpn_AccountLimit'
        ), $param);
        return $this->findManyToOne($request, $match, $myParams);
    }

    public function delete($request, $match, $param)
    {
        $account = Vpn_Util::extractAccountOr404($request, $match);
        $match['parentId'] = $account->id;
        $myParams = array_merge(array(
            'parent' => 'Vpn_Account',
            'parentKey' => 'account_id',
            'model' => 'Vpn_AccountLimit'
        ), $param);
        return $this->deleteManyToOne($request, $match, $myParams);
    }
}

