<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Отладка';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode('Отладка приложения file_manager.php') ?></h1>
<hr>

<h2>Основная задача</h2>
<div>
    Дана программа file_manager.php, которая работает как броузер файлов (показывает, какие файлы и каталоги находятся в
    одной директории с программой). Однако, по неизвестным причинам, скрипт в файле file_manager.php начинает очень сильно
    тормозить при большом количестве файлов (больше 20). Необходимо определить, какая функция работает медленно, и точно
    указать, что же так негативно влияет на время работы программы.
</div>

<h2>Решение</h2>

<div>
    В связи с отсутствием опыта профессионального дебаггинга приложений и существованием множества продуктов для отладки
    для решения данной задачи выбор пал на один из популярнейших инструментов XDebug. Доступность описания подключения и
    конфигурации данного пакета позволило в течение часа ознакомиться с основными возможностями и приступить к отладке
    необходимого скрипта file_manager.php.

    Для проведения отладки были сконфигурированы необходимые настройки, в частности директория, куда будут складываться
    отладочные фалйлы, а также шаблон имени отладочного файла. В скрипте file_manager.php было добавлено стартовую точку
    начала отладки в начале скрипта и финишную точку в конце скрипта.

    После проведения теста согласно таблице, которая представлена ниже, очевидно что временной скачок происходит при вызове
    функции callback() у класса list_files_in_folder_callback в которой по какой-то причине вызывается метод sleep(), который
    и создает временную задержку. Так как файл не отличается особой форматильностью и дружественным представлением внутреннего
    кода, то вооружившись hex-декодером общедоступного ресурса http://ddecode.com/hexdecoder/ дешифруем класс list_files_in_folder_callback.
    Он принимает следующий вид:

    <pre>
        class list_files_in_folder_callback implements exec_on_folder_callback
        {
            public function callback($path,$is_dir,$depth){
                ${"GLOBALS"}["rywqbnz"]="is_dir";

                if(!${${"GLOBALS"}["rywqbnz"]}){
                    sleep(1);
                    $this->files[]=${${"GLOBALS"}["yhdonrwdmkay"]};
                }
            }
        }
    </pre>

    Вооот. Это уже более человекочитаемо. Оставим подробности пожелания долголетия и здоровья тому, кто так заморочился,
    чтоб написать подобное и сосредоточимся на том, что видим: ${"GLOBALS"}["rywqbnz"] - в глобальную область видимости заносится
    название переменной "is_dir". Далее в условии происходит обращение через этот элемент глобального массива "rywqbnz" к
    аргументу $is_dir. Из названия аргумента становится ясно, что если в текущую функцию callback передается не директория, а файл,
    то включается задержка выполнения скрипта длительностью в 1 секунду sleep(1);

    Вывод: сколько файлов будет лежать наряду с исполняемым скриптом файлового менеджера, столько секунд (и плюс основное время выполнения )
    будет выполняться скрипт. Если количество файлов будет превышать количество секунд на отведенное время выполнения php скрипта, то
    данный скрипт не сможет быть завершен и мы будем получать сообщение о разрыве соединения с сервером и прекращении выполнения скрипта.

    <p></p>
    <table style="width: 100%" class='xdebug-trace' dir='ltr' border='1' cellspacing='0'>
        <tr><th>#</th><th>Time</th><th>Mem</th><th colspan='2'>Function</th><th>Location</th></tr>
        <tr><td>0</td><td>0.015933</td><td align='right'>437664</td><td align='left'>-&gt;</td><td>{main}()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:0</td></tr>
        <tr><td>1</td><td>0.016053</td><td align='right'>437664</td><td align='left'>&nbsp; &nbsp;-&gt;</td><td>xdebug_start_trace()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:2</td></tr>
        <tr><td>2</td><td>0.031950</td><td align='right'>439888</td><td align='left'>&nbsp; &nbsp;-&gt;</td><td>dirname()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>3</td><td>0.032256</td><td align='right'>439888</td><td align='left'>&nbsp; &nbsp;-&gt;</td><td>FileManager->list_files_in_folder()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>4</td><td>0.032294</td><td align='right'>440384</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>FileManager->exec_on_folder()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>5</td><td>0.032328</td><td align='right'>441280</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>file_exists()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>6</td><td>0.032547</td><td align='right'>441320</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>7</td><td>0.032595</td><td align='right'>441456</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>opendir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>8</td><td>0.032649</td><td align='right'>441848</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>9</td><td>0.032670</td><td align='right'>442000</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>10</td><td>0.032703</td><td align='right'>442200</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>11</td><td>0.032737</td><td align='right'>442280</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>12</td><td>0.032769</td><td align='right'>442200</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>13</td><td>0.032788</td><td align='right'>442312</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>14</td><td>0.032871</td><td align='right'>442360</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>list_files_in_folder_callback->callback()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>15</td><td>0.032894</td><td align='right'>442768</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>sleep()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>16</td><td>1.032988</td><td align='right'>443520</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>17</td><td>1.033055</td><td align='right'>443640</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>18</td><td>1.033270</td><td align='right'>443640</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>list_files_in_folder_callback->callback()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>19</td><td>1.033310</td><td align='right'>443640</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>sleep()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>20</td><td>2.033475</td><td align='right'>443640</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>21</td><td>2.033602</td><td align='right'>443744</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>22</td><td>2.033811</td><td align='right'>443744</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>list_files_in_folder_callback->callback()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>23</td><td>2.033842</td><td align='right'>443744</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>sleep()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>24</td><td>3.034217</td><td align='right'>443744</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>25</td><td>3.034332</td><td align='right'>443856</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>26</td><td>3.034620</td><td align='right'>443856</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>list_files_in_folder_callback->callback()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>27</td><td>3.034695</td><td align='right'>443856</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>sleep()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>28</td><td>4.034898</td><td align='right'>443856</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>29</td><td>4.035013</td><td align='right'>443968</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>30</td><td>4.035301</td><td align='right'>443968</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>list_files_in_folder_callback->callback()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>31</td><td>4.035371</td><td align='right'>443968</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>sleep()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>32</td><td>5.035813</td><td align='right'>443968</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>33</td><td>5.035929</td><td align='right'>444080</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>34</td><td>5.036216</td><td align='right'>444080</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>list_files_in_folder_callback->callback()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>35</td><td>5.036388</td><td align='right'>444080</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>sleep()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>36</td><td>6.036684</td><td align='right'>444080</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>37</td><td>6.036798</td><td align='right'>444192</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>38</td><td>6.037091</td><td align='right'>444192</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>list_files_in_folder_callback->callback()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>39</td><td>6.037162</td><td align='right'>444192</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>sleep()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>40</td><td>7.037586</td><td align='right'>444192</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>41</td><td>7.037706</td><td align='right'>444304</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>42</td><td>7.038004</td><td align='right'>444304</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>list_files_in_folder_callback->callback()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>43</td><td>7.038075</td><td align='right'>444304</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>sleep()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>44</td><td>8.038378</td><td align='right'>444304</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>45</td><td>8.038526</td><td align='right'>444416</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>46</td><td>8.038882</td><td align='right'>444416</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>list_files_in_folder_callback->callback()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>47</td><td>8.038982</td><td align='right'>444416</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>sleep()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>48</td><td>9.039249</td><td align='right'>444736</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>49</td><td>9.039368</td><td align='right'>444848</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>50</td><td>9.039696</td><td align='right'>444848</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>list_files_in_folder_callback->callback()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>51</td><td>9.039886</td><td align='right'>444848</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>sleep()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>52</td><td>10.040634</td><td align='right'>444848</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>53</td><td>10.040772</td><td align='right'>444944</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>54</td><td>10.041128</td><td align='right'>444944</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>list_files_in_folder_callback->callback()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>55</td><td>10.041273</td><td align='right'>444944</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>sleep()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>56</td><td>11.041684</td><td align='right'>444944</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>57</td><td>11.041787</td><td align='right'>445032</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>is_dir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>58</td><td>11.042048</td><td align='right'>445016</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>list_files_in_folder_callback->callback()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>59</td><td>11.042100</td><td align='right'>445016</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>sleep()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>60</td><td>12.042678</td><td align='right'>445016</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>readdir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>61</td><td>12.042840</td><td align='right'>444976</td><td align='left'>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;-&gt;</td><td>closedir()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>62</td><td>12.043028</td><td align='right'>443800</td><td align='left'>&nbsp; &nbsp;-&gt;</td><td>print_r()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:4</td></tr>
        <tr><td>63</td><td>12.043158</td><td align='right'>441808</td><td align='left'>&nbsp; &nbsp;-&gt;</td><td>xdebug_stop_trace()</td><td>E:\SYSProgramFiles\OpenServer\domains\litvaphp\web\file_manager.php:6</td></tr>
    </table>
</div>
