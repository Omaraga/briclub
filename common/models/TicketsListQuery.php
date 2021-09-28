<?php


namespace common\models;

/**
 * This is the ActiveQuery class for [[UsersList]].
 *
 * @see TicketsList
 */

class TicketsListQuery extends \yii\db\ActiveQuery
{
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