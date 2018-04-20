<?php if (!defined('THINK_PATH')) exit();?><table width="90%" align="center" >
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td class="label" width="10%"><?php if(($vo["attr_type"]) == "2"): ?><a href="javascript:;" onclick="clonethis(this)">[+]</a><?php endif; echo ($vo["attr_name"]); ?>：</td>
        <td>
        <?php if(($vo["attr_input_type"]) == "1"): ?><input type="text" name="attr[<?php echo ($vo["id"]); ?>][]">
        <?php else: ?>
            <select name="attr[<?php echo ($vo["id"]); ?>][]" id="type_id">
                <?php if(is_array($vo["attr_values"])): $i = 0; $__LIST__ = $vo["attr_values"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select><?php endif; ?>
            
        </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>