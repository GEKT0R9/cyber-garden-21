<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */
use app\assets\LoginAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
LoginAsset::register($this);
$this->params['breadcrumbs'][] = $this->title;
?>

<!DOCTYPE html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="document-state" content="Dynamic"><link rel="shortcut icon" href="/favicon.png"><link rel="stylesheet" href="css/common.css"><style>body {
    padding: 0;
    margin: 0;
}

.content-body {
    background: gray;
    min-height: 100vh;
    display: flex;
}

.modal {
    background: white;
    padding: 1rem 3rem;
    border-radius: 8px;
    box-shadow: 2px 2px 8px #0002;
    text-align: center;
    max-width: 600px;
    margin: auto;
}

.logo img {
    max-width: 20%;
}

.btn {
    background: #3751ff;
    box-shadow: 0 2px 6px #3751ffaa;
    padding: 1rem 2rem;
    border-radius: 8px;
    cursor: pointer;
    display: block;
    margin: 1rem 0;
    letter-spacing: 0.4px;
    color: #ffffff;
    font-weight: 600;
    transition: all .1s ease-in;
}

.btn:hover {
    transform: translateY(-2px);
    background: #3776ff;
    box-shadow: 0 2px 6px rgba(55, 102, 255, 0.67);
}

h2 {
    font-weight: bold;
    letter-spacing: 0.4px;
}

h3 {
    font-weight: bold;
    color: #a4a6b3;
    letter-spacing: 0.6px;
}

label {
    display: block;
}

.inp {
    padding: 1rem 0;
}

.inp label {
    letter-spacing: 0.3px;
    text-transform: uppercase;
    color: #9fa2b4;
    font-weight: bold;
    text-align: left;
}

.inp a {
    color: #9fa2b4;
}

.desc {
    color: #9fa2b4;
    letter-spacing: 0.3px;
}

input[type='text'], input[type='password'] {
    border-radius: 8px;
    border: 2px solid #f0f1f7;
    background: #fcfdfe;
    display: block;
    width: 100%;
    box-shadow: 0 0 0 white;
    padding: 1rem;
    transition: all .2s ease-out;
}

input[type='text']:focus, input[type='password']:focus {
    border: 2px solid #3751ff;
    box-shadow: 0 1px 6px #3751ff55;
}
</style>
</head>
<body>
    <div class="content-body">
        <div class="modal card">
            <div class="logo">
                <img src="img/logo.png">
                <h3>ЛолКекЧебурек</h3>
            </div>
            <h2>Вход</h2>
            <?php $form = ActiveForm::begin(); ?>
                <div class="inp">
                    <label>Логин</label>
                    <?=$form->field($model, 'username')->textInput(['autofocus' => true,'placeholder' => 'Введите логин'])->label(false)->error(false) ?>
                </div>
                <div class="inp">
                    <div style="display:flex; justify-content: space-between;">
                        <label>Пароль</label>
                        <a>Забыли пароль?</a>
                    </div>
                    <?=$form->field($model, 'password')->passwordInput(['placeholder' => "Введите пароль"])->label(false)->error(false) ?>

                </div>
                <?= Html::submitButton('Войти', ['id' => 'send-btn', 'class' => 'btn']) ?>
                <?= $form->errorSummary($model, ['class' => 'error']) ?>
                <?php ActiveForm::end(); ?>
            <p class="desc">Нет аккаунта?<br>Подойдите к администратору вашей организации</p>
        </div>
    </div>
</body>
</html>