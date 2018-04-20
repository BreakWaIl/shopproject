<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 商品列表</title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>

<h1>
    <span class="action-span"><a href="#">商品分类</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 添加分类 </span>
    <div style="clear:both"></div>
</h1>

<div class="main-div">
    
<form method="post" action="" name="listForm">
    <div class="list-div" id="listDiv">
        <table width="100%" cellspacing="1" cellpadding="2" id="list-table">
            <tr>
                <th>权限名称</th>
                <th>模块名称</th>
                <th>控制器名称</th>
                <th>操作名称</th>
                <th>是否显示</th>
                <th>操作</th>
            </tr>  
            <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr align="center" class="0">
                <td align="left" class="first-cell" >                
                   <?php echo (str_repeat('&nbsp;&nbsp;',$vo["lev"])); echo ($vo["rule_name"]); ?>
                </td>
                <td align="center" ><?php echo ($vo["module_name"]); ?></td>
                <td align="center" ><?php echo ($vo["controller_name"]); ?></td>
                <td align="center" ><?php echo ($vo["action_name"]); ?></td>
                <td align="center" ><?php if(($vo["is_show"]) == "1"): ?>显示<?php else: ?>不显示<?php endif; ?></td>
                <td width="30%" align="center">
                    <a href="<?php echo U('edit','rule_id='.$vo['id']);?>">编辑</a> |
                    <a href="<?php echo U('dels','rule_id='.$vo['id']);?>" title="移除" onclick="">移除</a>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
    </div>
</form>

</div>
<div id="footer">
共执行 3 个查询，用时 0.162348 秒，Gzip 已禁用，内存占用 2.266 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>

</body>
</html>
<script type="text/javascript" src="/Public/Admin/Js/jquery-1.8.3.min.js"></script>