<?php
/**
 * Created by PhpStorm.
 * User: 32
 * Date: 10.02.2017
 * Time: 18:59
 */

$errorMessages = [];
$fileName = 'userComments.dat';

if ($_POST) {
    if (empty($_POST['uName'])) {
        $errorMessages[] = 'Username is empty!';
    }
    if (empty($_POST['uMail'])) {
        $errorMessages[] = 'User email is empty!';
    } elseif (!filter_var($_POST['uMail'], FILTER_VALIDATE_EMAIL)) {
            $errorMessages[] = 'User email has wrong forat!';
    }
    if (empty($_POST['uComment'])) {
        $errorMessages[] = 'User comment is empty!';
    }
    if (empty($errorMessages)) {
        setComments($_POST);
    }
}

function getComments()
{
    global $fileName;
    if (file_exists($fileName)) {
        return file_get_contents($fileName);
    }
}

function setComments($data)
{
    global $fileName;
    $prevComments = getComments();
    if (($prevComments !== false) && (!empty($prevComments))) {
        $aPrevData = unserialize($prevComments);
    } else {
        $aPrevData = [];
    }
    $data['addedDate'] = date('Y-m-d H:i:s');
    $aNewData = array_merge([$data], $aPrevData);
  //  echo '<p>Debug all comments: </p><pre>' . var_export($aNewData, 1) . '</pre>';
    $sData = serialize($aNewData);
    file_put_contents($fileName, $sData);
}

function getErrorMessages()
{
    global $errorMessages;
    if (! empty($errorMessages)) {
        foreach ($errorMessages as $error) {
            echo '<p class="error">' . $error . '</p>';
        }
    }
}

function displayComments()
{
    $sComm = getComments();
    if ($sComm !== false) {
        $aComm = unserialize($sComm);
        if (!empty($aComm)) {
            foreach ($aComm as $comm) {
                echo '<div class="comment">' .
                        '<p><span class="date">' . $comm['addedDate'] . '</span> Автор:  <a href="mailto:' . $comm['uMail'] . '">' . $comm['uName'] . '</a>:</p>' .
                        '<div class="comment-text">' . antimat($comm['uComment']) . '</div>'.
                    '</div>';
            }
        }
    }
}

function antimat($str)
{
    return str_replace([
            'Choko',
            'test',
        ], [
            'Ch***',
            't***',
        ], $str);
}

getErrorMessages()
?>

<hr>

<h2>Добавьте комментарий</h2>

<form action="" method="post">
    <div >
        <div class="comments"><label for="uName">Имя пользователя *: </label></div>
        <input style="width: 200px" type="text" name="uName">
    </div>
    <br>
    <div >
        <div class="comments"> <label for="uMail">Email *: </label></div>
        <input style="width: 200px" type="email" name="uMail">
    </div>
    <br>
    <div >
        <div class="comments"><label for="uComment">Комментарий *: </label></div>
        <textarea style="width: 200px" name="uComment"></textarea>
    </div>
    <button type="submit">Добавить комментарий </button>
</form>
<p>* - обязательное поле</p>

<hr>

<?=displayComments()?>

