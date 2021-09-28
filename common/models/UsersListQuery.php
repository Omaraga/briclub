<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[UsersList]].
 *
 * @see UsersList
 */
class UsersListQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UsersList[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UsersList|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
