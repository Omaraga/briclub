<?php


namespace common\models;


use common\models\TransfersList;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[UsersList]].
 *
 * @see TransfersList
 */


class TransfersListQuery extends ActiveQuery
{
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TransfersList|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}