@include('zng::common.header')

<body class="childrenBody">
    <form>
        <table id="RoleList" lay-filter="RoleList"></table>
    </form>
</body>
<script type="text/html" id="RoleTableBar">
    <div class="layui-inline">
        <div class="layui-input-inline">
            <input type="text" class="layui-input searchVal" placeholder="请输入搜索的内容" />
        </div>
        <button type="button" class="layui-btn search_btn layui-btn-sm" lay-event="search" >搜索</button>
    </div>
    <button type="button" class="layui-btn search_btn layui-btn-sm" lay-event="clearSeach" >展示所有</button>
    <button type="button" class="layui-btn layui-btn-normal layui-btn-sm" lay-event="add">增加</button>

</script>
<!--操作-->
<script type="text/html" id="newsListBar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
</script>

@include('zng::common.footer')
@include('zng::{{name}}.js')



