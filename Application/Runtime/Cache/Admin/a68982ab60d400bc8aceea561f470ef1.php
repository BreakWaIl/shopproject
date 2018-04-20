<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 商品分类</title>
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
    
<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front">通用信息</span>
            <span class="tab-front">详细信息</span>
            <span class="tab-front">商品属性</span>
            <span class="tab-front">商品相册</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="" method="post">
            <table width="90%" class="table" align="center">
                <tr>
                    <td class="label">商品名称：</td>
                    <td><input type="text" name="goods_name" value="<?php echo ($info["goods_name"]); ?>"/>
                    <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">商品货号： </td>
                    <td>
                        <input type="text" name="goods_sn" value="<?php echo ($info["goods_sn"]); ?>" />
                        <span id="goods_sn_notice"></span><br />
                        <span class="notice-span"id="noticeGoodsSN">如果您不输入商品货号，系统将自动生成一个唯一的货号。</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品数量： </td>
                    <td>
                        <input type="text" name="goods_number" value="<?php echo ($info["goods_number"]); ?>" />
                        
                    </td>
                </tr>
                <tr>
                    <td class="label">商品分类：</td>
                    <td>
                        <select name="cate_id">
                            <?php if(is_array($tree)): $i = 0; $__LIST__ = $tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($info["cate_id"]) == $vo["id"]): ?>selected="selected"<?php endif; ?>><?php echo (str_repeat('&nbsp;',$vo["lev"])); echo ($vo["cname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <span class="require-field">*</span>
                    </td>
                </tr>

                <tr>
                    <td class="label">本店售价：</td>
                    <td>
                        <input type="text" name="shop_price" value="<?php echo ($info["shop_price"]); ?>" />
                        <span class="require-field">*</span>
                    </td>
                </tr>
        
                <tr>
                    <td class="label">加入推荐：</td>
                    <td>
                        <input type="checkbox" name="is_hot" value="1" <?php if(($info["is_hot"]) == "1"): ?>checked="checked"<?php endif; ?> /> 热卖 
                        <input type="checkbox" name="is_new" value="1" <?php if(($info["is_new"]) == "1"): ?>checked="checked"<?php endif; ?> /> 新品 
                        <input type="checkbox" name="is_rec" value="1" <?php if(($info["is_rec"]) == "1"): ?>checked="checked"<?php endif; ?>/> 推荐
                    </td>
                </tr>

                <tr>
                    <td class="label">市场售价：</td>
                    <td>
                        <input type="text" name="market_price" value="<?php echo ($info["market_price"]); ?>"  />
                    </td>
                </tr>

                <tr>
                    <td class="label">商品图片：</td>
                    <td>
                        <input type="hidden" id="goods_img" name="goods_img"  value="<?php echo ($info["goods_img"]); ?>" />
                        <input type="hidden" name="goods_thumb" id="goods_thumb" value="<?php echo ($info["goods_thumb"]); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="label">&nbsp;</td>
                    <td>
                        <!-- 当图片上传完之后显示图片 -->
                        <img src="/<?php echo ($info["goods_img"]); ?>" width="100" height="100" class="goods_img">
                    </td>
                </tr>
            </table>
            
            <table width="90%" class="table" align="center" style="display: none" >
                <tr>
                    <td class="label" width="10%">商品详情：</td>
                    <td>
                        <!-- 加载编辑器的容器 -->
                        <script id="container" name="goods_body" type="text/plain" style="width: 90%;height: 300px;"><?php echo ($info["goods_body"]); ?></script>
                        <!-- 配置文件 -->
                        <script type="text/javascript" src="/Public/ueditor/ueditor.config.js"></script>
                        <!-- 编辑器源码文件 -->
                        <script type="text/javascript" src="/Public/ueditor/ueditor.all.js"></script>
                        <!-- 实例化编辑器 -->
                        <script type="text/javascript">
                            var ue = UE.getEditor('container');
                        </script>
                    </td>
                </tr>
            </table>
            <table width="90%" class="table" align="center" style="display: none" >
                <tr>
                    <td class="label" width="10%">选项类型：</td>
                    <td>
                        <select name="type_id" id="type_id">
                            <option>请选择类型</option>
                            <?php if(is_array($type)): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($info["type_id"]) == $vo["id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" id="showAttr">
                        <table width="90%" align="center" >
                            <?php if(is_array($attr)): $i = 0; $__LIST__ = $attr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if(is_array($v)): $i = 0; $__LIST__ = $v;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                    <td class="label"><?php if(($vo["attr_type"]) == "2"): ?><a href="javascript:;" onclick="clonethis(this)"><?php if(($i) == "1"): ?>[+]<?php else: ?>[-]<?php endif; ?></a><?php endif; echo ($vo["attr_name"]); ?>:</td>
                                    <td>
                                       <?php if(($vo["attr_input_type"]) == "1"): ?><input type="text" name="attr[<?php echo ($vo["attr_id"]); ?>][]" value="<?php echo ($vo["attr_value"]); ?>" />
                                        <?php else: ?>
                                        <select name="attr[<?php echo ($vo["attr_id"]); ?>][]">
                                            <?php if(is_array($vo["attr_values"])): $i = 0; $__LIST__ = $vo["attr_values"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option <?php if(($vo["attr_value"]) == $v): ?>selected="selected"<?php endif; ?>><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select><?php endif; ?>
                                    </td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="90%" class="table pic" align="center" style="display: none" >
                <tr>
                    <td colspan="2">
                        <?php if(is_array($goods_img_list)): $i = 0; $__LIST__ = $goods_img_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div style="float: left;margin: 0 10px;"><img src="/<?php echo ($vo["goods_thumb"]); ?>" width="100" height="100" data-goods-id="<?php echo ($vo["id"]); ?>"></div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="label" width="10%"></td>
                    <td>
                       <input type="button" id="addPic" value="增加图片">
                    </td>
                </tr>
                <tr>
                    <td class="label" width="10%">上传图片：</td>
                    <td>
                        <input type="file" name="pic[]">
                    </td>
                </tr>
            </table>
            <input type="hidden" name="goods_id" value="<?php echo ($info["id"]); ?>" />
            <div class="button-div">
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
    </div>
</div>

</div>
<div id="footer">
共执行 3 个查询，用时 0.162348 秒，Gzip 已禁用，内存占用 2.266 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>

</body>
</html>
<script type="text/javascript" src="/Public/Admin/Js/jquery-1.8.3.min.js"></script>

    <script type="text/javascript" src="/Public/layui/layui.js"></script>
    <script type="text/javascript">
        // 文件异步上传
        layui.use('upload', function(){
            var upload = layui.upload;   
            //执行实例
            var uploadInst = upload.render({
                elem: '#goods_img',//绑定的元素 
                url: '<?php echo U("upload");?>', //上传接口
                done: function(res){
                    // 文件上传成功处理
                    if(res.status==1){
                        //显示上传的图片
                        $('.goods_img').attr('src','/'+res.goods_img);
                        // 处理表单提交的商品图片及缩略图
                        $('#goods_img').val(res.goods_img);
                        $('#goods_thumb').val(res.goods_thumb);
                    }else{
                        layer.alert(res.msg);
                    }            
                }
    
            });
        });
    </script>
    <script type="text/javascript">
        // 实现选项卡的切换效果
        $('#tabbar-div p span').click(function(){
            // 将所有的table隐藏
            $('.table').hide();
            // 将当前点击对应的table显示
            // 当前点击的span的索引
            var i = $(this).index();
            // 根据索引找到对应的table显示
            $('.table').eq(i).show();
        });
        // 类型切换获取属性信息
        $('#type_id').change(function(){
            //获取当前选中的类型的id
            var type_id = $(this).val();
            $.ajax({
                url:'<?php echo U("showAttr");?>',
                data:{type_id:type_id},
                type:'post',
                success:function(res){
                    // 处理成功之后将结果以html格式的字符串进行返回
                    // 此格式最为方便。
                    $('#showAttr').html(res);
                }
            });
        });
        // 实现自身的复制或者删除功能
        function clonethis(obj){
            // 获取点击的完整tr对象
            var current = $(obj).parent().parent();
            if($(obj).html()=='[+]'){
                // 复制并且追加
                var newtr = current.clone(true);
                // 将新的符号修改为[-]
                newtr.find('a').html('[-]');
                current.after(newtr);
            }else{
                // 删除当前点击的
                current.remove();
            }
        }
        // 实现点击增加图片对上传的file进行克隆
        $('#addPic').click(function(){
            // 寻找第一个相册上传的tr进行克隆
            var newtr = $(this).parent().parent().next().clone();
            // 找到table追加
            $('.pic').append(newtr);
        });
        $('.pic img').click(function(){
            var goods_img_id = $(this).attr('data-goods-id');
            var obj = $(this).parent(); 
            $.ajax({
                url:'<?php echo U("delImg");?>',
                data:{goods_img_id:goods_img_id},
                type:'post',
                success:function(res){
                    if(res.status==1){
                        //移除当前的dom元素
                        obj.remove();
                    }
                }
            });
        });
    </script>