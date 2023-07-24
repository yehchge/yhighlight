<?php declare(strict_types=1);

namespace yehchge\yhighlight;

include "../src/highlight.php";

$hl = new highlight();

$hl->setDebug(false);

$hl->setLeftHtml("<div style='background:yellow;'>");
$hl->setRightHtml("</div>");

$aTitle[] = "";
$aKeywords[] = "";

$aTitle[] = "";
$aKeywords[] = array("");

$aTitle[] = "";
$aKeywords[] = array("籃球");

$aTitle[] = "棒球之神大谷翔平！數據超越上古神獸";
$aKeywords[] = array('');

$aTitle[] = "棒球之神大谷翔平！數據超越上古神獸";
$aKeywords[] = array("籃球");

$aTitle[] = "棒球之神大谷翔平！數據超越上古神獸";
$aKeywords[] = array("棒球");

$aTitle[] = "棒球之神大谷翔平！數據超越上古神獸";
$aKeywords[] = array("大谷");

$aTitle[] = "棒球之神大谷翔平！數據超越上古神獸";
$aKeywords[] = array("神獸");

$aTitle[] = "棒球之神大谷翔平！數據超越上古神獸";
$aKeywords[] = array("神獸", "大谷");

$aTitle[] = "棒球之神大谷翔平！數據超越上古神獸";
$aKeywords[] = array("大谷翔平", "神獸", "大谷");

$aTitle[] = "棒球之神大谷翔平！數據超越上古神獸";
$aKeywords[] = array("大谷", "翔平", "神獸");

$aTitle[] = "棒球之神大谷翔平！數據超越上古神獸";
$aKeywords[] = array("大谷", "神獸", "翔平");

$aTitle[] = "棒球之神大谷翔平！數據超越上古神獸";
$aKeywords[] = array("大谷", "大谷翔平", "神獸", "大谷");

$aTitle[] = "棒球之神大谷翔平！數據超越上古神獸";
$aKeywords[] = array("二刀流", "大谷", "運動", "棒球");

$aTitle[] = "棒球之神大谷翔平！數據超越上古神獸";
$aKeywords[] = array("大谷翔平", "翔平", "谷翔");

$aTitle[] = "吃葡萄不吐葡萄皮，不吃葡萄倒吐葡萄皮";
$aKeywords[] = array("葡萄");

$aTitle[] = "吃葡萄不吐葡萄皮，不吃葡萄倒吐葡萄皮";
$aKeywords[] = array("葡萄", "葡萄皮");

$aTitle[] = "超人氣小雞幸福大書包";
$aKeywords[] = array("小雞", "人氣", "超人", "超人氣");

$aTitle[] = "過敏喝了不會有問題的羊奶粉";
$aKeywords[] = array("羊奶","過敏","奶粉");

$aTitle[] = "Do you know any funny jokes";
$aKeywords[] = array('funny', 'fun', 'joke');


foreach($aTitle as $key => $title){
    $keywords = $aKeywords[$key];

    $str = $hl->highlightKeywords($title, $keywords);
    echo $str.PHP_EOL;
}
