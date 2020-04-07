<script>

    var roleIndexUrl ="{{route('code.index')}}",//列表数据
        roleChangeStatusUrl="{{route('code.status')}}",
        roleDelUrl = "{{route('code.del')}}",
        roleAddUrl = "{{route('code.add')}}",
        roleSaveUrl = "{{route('code.save')}}",
        doAddUrl = "{{route('code.doAdd')}}",
        doSaveUrl="{{route('code.doSave')}}",
        modeName = "代码生成器",
        ajaxListUrl = "{{route('code.ajaxList')}}",
        pidList = "{{(isset($data)&& !empty($data['space_id']))?$data['space_id']:'0' }}",
        completeCodeUrl="{{route('code.completeCode')}}",
        moudelUrlAjax="{{route('code.moudel')}}",
        mod_ids = "{{(isset($data)&& !empty($data['mod_id']))?$data['mod_id']:'0' }}"
</script>
<script>
    layui.use(['form','layer','laydate','table','laytpl','selectN'],function () {
        var kyewords=''
        var form = layui.form,
            layer = parent.layer === undefined ? layui.layer : top.layer,
            $ = layui.jquery,
            table = layui.table,
            selectN= layui.selectN;

        var cate =selectN({
            elem: '#space',
            name:'space_id'
            //候选数据【必填】
            ,data: ajaxListUrl,
            selected:pidList,
        })
        var module =selectN({
            elem: '#module',
            name:'mod_id'
            //候选数据【必填】
            ,data: moudelUrlAjax,
            selected:mod_ids,
        })
        module.set([mod_ids])
        var roleTable = table.render({
            elem: '#RoleList',
            url : roleIndexUrl,
            method:'get',
            where:{
                keywords:kyewords
            },
            title:'角色列表',
            toolbar:'#RoleTableBar',
            cellMinWidth : 95,
            page : true,
            loading:true,
            height : "full-125",
            limit : 20,
            limits : [2,10,15,20,25,50,75,100],
            id : "RoleList",
            text:{
                none:'暂无数据'
            },
            cols : [[
                {type: "checkbox", fixed:"left", width:50},
                {field: 'id', title: 'ID', width:60, align:"center",sort:true},
                {field: 'title', title: '名称', width:150},
                {field: 'name', title: '文件名称', width:150},
                {field: 'n_name', title: '命名空间名称', width:150},

                {field: 'sort', title: '排序', width:220,edit:'text',sort:true},
                {field: 'status', title: '状态',align:'center' ,width:120,templet:function (d) {
                        var checked='';
                        if (d.status==1){checked='checked'}
                        return '<input type="checkbox" name="status" value="'+d.id+'" lay-filter="status" lay-skin="switch" lay-text="正常|禁用" '+checked+'>'
                    }},
                {field: 'finish', title: '是否已生成',align:'center' ,width:180,templet:function (d) {
                        var checked='';
                        if (d.finish==1){checked='checked'}
                        return '<input type="checkbox" name="finish" value="'+d.id+'" lay-filter="finish" lay-skin="switch" lay-text="是|否" '+checked+'>'
                    }},
                {field: 'created_at', title: '创建时间', width:200,sort: true},
                {field: 'updated_at', title: '更新时间', width:200,sort:true},
                {title: '操作', width:240, templet:'#newsListBar',fixed:"right",align:"center"}
            ]]
        });
        table.on('sort',function (d) {
            console.log(d)
            table.reload('RoleList',{
                initSort:d,
                where:{
                    field: d.field, //排序字段
                    order: d.type, //排序方式,
                    keywords: kyewords
                }
            })
        })
        table.on('tool(RoleList)',function (d) {
            var tye=d.event;
            switch(tye){
                case 'del':
                    del(d)
                    break;
                case 'edit':
                    openUrlIframe('修改'+modeName,roleSaveUrl+"?id="+d.data.id)
                    break;
                case 'code_config':
                    CodeConfig(d)
                    break;
            }
        })
        function CodeConfig(d) {
            $.getJSON(completeCodeUrl,{id:d.data.id},function (r) {
                if (r.statusCode == 200) {
                    layer.msg(r.message, {time: 1500, icon: 1});
                    table.reload('RoleList')
                } else {
                    layer.msg(r.message, {time: 1500, icon: 2});
                }
            });
            return false;

        }
        function tableFileList(d) {
            //window.location.href=fieldIndexUrl+"?mid="+d.data.id
        }
        function delAll() {
            layer.confirm('确认要删除选中信息吗？', {icon: 3}, function(index) {
                layer.close(index);
                var checkStatus = table.checkStatus('RoleList'); //test即为参数id设定的值
                var ids = [];
                $(checkStatus.data).each(function (i, o) {
                    ids.push(o.id);
                });
                console.log(ids)
                if (ids.length==0){
                    layer.msg('请选择元素！', {time: 1500, icon: 2});
                    return false
                }
                // var loading = layer.load(1, {shade: [0.1, '#fff']});
                $.post(roleDelAllUrl, {ids: ids}, function (data) {
                    // layer.close(loading);
                    if (data.statusCode == 200) {
                        layer.msg(data.message, {time: 1500, icon: 1});
                        table.reload('RoleList')
                        $('.searchVal').val(kyewords)
                    } else {
                        layer.msg(data.message, {time: 1500, icon: 2});
                    }
                });
            });
        }


        function openUrlIframe(title,url) {
            var index = layui.layer.open({
                title : title,
                type : 2,
                content : url,
                success : function(layero, index){
                    var body = layui.layer.getChildFrame('body', index);
                    setTimeout(function(){
                        layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    },500)
                }
            })
            layui.layer.full(index);
            //改变窗口大小时，重置弹窗的宽高，防止超出可视区域（如F12调出debug的操作）
            $(window).on("resize",function(){
                layui.layer.full(index);
            })
        }

        function del(d){
            layer.confirm('你确定要删除吗？', function(index){
                $.post(roleDelUrl,{id:d.data.id},function(res){
                    if(res.statusCode==200){
                        layer.msg(res.message,{time:1000,icon:1});
                        d.del();
                    }else{
                        layer.msg(res.message,{time:1000,icon:2});
                    }
                });
                layer.close(index);
            });
        }
        table.on('toolbar(RoleList)',function (d) {
            var type =d.event
            kyewords=$('.searchVal').val().trim()
            switch (type) {
                case 'search':
                    table.reload('RoleList',{
                        where:{
                            keywords: kyewords
                        }
                    })
                    $('.searchVal').val(kyewords)
                    break;
                case 'clearSeach':
                    kyewords=''
                    table.reload('RoleList',{
                        where:{
                            keywords: kyewords
                        }
                    })
                    $('.searchVal').val(kyewords)
                    break;
                case 'add':
                    openUrlIframe('添加'+modeName,roleAddUrl)
                    break;
            }

        })
        form.on('switch', function(obj){
            console.log(obj)
            console.log(obj.elem.name)
            //loading =layer.load(1, {shade: [0.1,'#fff']});
            var id = this.value;
            var name = obj.elem.name
            var authopen = obj.elem.checked===true?1:0;
            $.post(roleChangeStatusUrl,{id:id,status:authopen,name:name},function (res) {
                //  layer.close(loading);
                if (res.statusCode==200) {
                    //roleTable.reload();
                    // layer.msg('成功')
                }else{
                    layer.msg(res.message,{time:1000,icon:2});
                }
                $('.searchVal').val(kyewords)
                return false
            })
        })

        table.on('edit(RoleList)',function (d) {

          var   id  = d.data.id,
                status = d.value,
                name = d.field
        $.post(roleChangeStatusUrl,{id:id,status:status,name:name},function (res) {
            if (res.statusCode==200) {
            }else{
                layer.msg(res.message,{time:1000,icon:2});
            }
            $('.searchVal').val(kyewords)
            return false
        })
            return false
        })

        //添加
        form.on('submit(add)',function (d) {
            $.post(doAddUrl,d.field,function (r) {
                if(r.statusCode===200){
                    top.layer.msg(r.message);
                    layer.closeAll("iframe");
                    parent.location.reload();
                }else{
                    top.layer.msg(r.message);
                }
                return false
            },'json')
            return false
        })
        //修改
        form.on('submit(save)',function (d) {
            $.post(doSaveUrl,d.field,function (r) {
                if(r.statusCode==200){
                    top.layer.msg(r.message);
                    layer.closeAll("iframe");
                    //刷新父页面
                    parent.location.reload();
                }else{
                    top.layer.msg(r.message);
                }
                return false
            },'json')

            return false;
        })
    })

</script>
