<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ty
 * Date: 14-6-25
 * Time: 下午2:33
 * To change this template use File | Settings | File Templates.
 */

?>
<h2>添加视频</h2>
<p>说明：请先选择类型，如果是用户单独的资源，请填写用户的用户名（学号）</p>
<p>
    <label>日期</label>
    <input type="date" id="resourceDate">
</p>

<p>
    <label>用户名（学号，如果是公共资源请勿填写）</label>
    <br>
    <input type="text" id="userName">
</p>

<p>
    <label>类型</label>
    <select id="resourceType">
        <option value="1">上课资料</option>
        <option value="2">试题资料</option>
    </select>
</p>
<hr>
<div id="uploadContainer">
    <input type="button" id="uploadBtn" value="上传">
    <p id="uploadProgress"></p>
</div>