<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "action_types".
 *
 * @property int $id
 * @property string $title
 * @property int $minus
 */




class ActionTypes extends \yii\db\ActiveRecord
{
    const ACTIVATION_SHANYRAK = 1;
    const VYVOD_DENEG= 2;
    const PEREVOD_NA_DRUGOI_ACCAUNT = 3;
    const POSTUPLENIE_S_DRUGOGO_ACCAUNT = 4;
    const POPOLNENIE_ADMIN = 5;
    const POPOLNENIE_PERFECT_MONEY = 6;
    const BONUS_ZA_POLZOVATELYA_SHANYRAK = 7;
    const ACTIVATION_MESTA = 9;
    const POKUPKA_MESTA = 10;
    const NEW_CLONE = 11;
    const OTMENA_VYVODA = 22;
    const OTMENA_PEREVODA = 33;
    const OTMENA_PEREVODA2 = 44;
    const POKUPKA_TOKENOV = 55;
    const PEREVOD_TOKENOV = 56;
    const POSTUPLENIE_TOKENOV = 57;
    const SPISANIE_KOMISSII_V_TOKENAH_ZA_PEREVOD_US = 58;
    const SPISANIE_KOMISSII_V_TOKENAH_ZA_POKUPKU_STOLA = 59;
    const SPISANIE_KOMISSII_V_TOKENAH_ZA_TEH_PODDERZHKU = 60;
    const SPISANIE_KOMISSII_V_TOKENAH_ZA_PEREVOD_GRC = 61;
    const VOZNAGRAZHDENIE_V_TOKENAH = 62;
    const POPOLNENIE_TOKENOV_ADMIN = 63;
    const VYSTOVLENIE_SCHETA = 64;
    const OPLATA_SCHETA = 65;
    const POLUCHENIE_DENEG_ZA_OPLACHENNYI_SCHET = 66;
    const OPLATA_SCHETA_GREENSWOP = 67;
    const OPLATA_SCHETA_BESROIT = 68;
    const GREENSWOP = 69;
    const VYSTAVLENIE_SCHETA_BESROIT = 70;
    const POGASHENIE_DOLGA = 96;
    const ACTIVATION_PROGRAMMY_SHANYRAK = 97;
    const PLATEZH_PO_PROGRAMME_SHANYRAK = 98;
    const VOZVRAT_ZA_OVERDRAFT = 101;
    const POPOLNENIE_VISA_MASTERCARD = 102;
    const POPOLNENIE_YANDEX = 103;
    const KURORTNYI_BONUS = 105;
    const ACTIVATION_BONUS = 106;
    const DOLG = 200;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'action_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['minus'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'minus' => 'Minus',
        ];
    }
}
