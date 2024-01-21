<?php
$numbers = range(40, 50); // 创建一个包含40到50的数字数组
shuffle($numbers); // 将数组随机排序
$selectedNumbers = array_slice($numbers, 0, 3); // 从数组中取前3个数字
print_r($selectedNumbers); // 打印结果