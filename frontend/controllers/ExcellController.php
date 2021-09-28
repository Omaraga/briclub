<?php

namespace frontend\controllers;
use yii\web\Controller;
use Yii;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use common\models\MatrixRef;
class ExcellController extends Controller
{

    public function actionIndex(){
        if (Yii::$app->user->isGuest){
            return $this->goHome();
        }
        $user = Yii::$app->user->identity;
        $matRef = MatrixRef::find()->where(['user_id'=>$user['id'],'platform_id'=>1])->orderBy('id asc')->one();

        //Создаем экземпляр класса электронной таблицы
        $spreadsheet = new Spreadsheet();
        //Получаем текущий активный лист
        $sheet = $spreadsheet->getActiveSheet();
        // Записываем в ячейку A1 данные
        $sheet->setCellValue('A1', 'Hello my Friend!');

        $writer = new Xlsx($spreadsheet);
        //Сохраняем файл в текущей папке, в которой выполняется скрипт.
        //Чтобы указать другую папку для сохранения.
        //Прописываем полный путь до папки и указываем имя файла
        $writer->save('hello.xlsx');

    }

}