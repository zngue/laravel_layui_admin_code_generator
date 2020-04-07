@include('zng::common.header')
<body class="childrenBody">
    <form class="layui-form" action="">
        {{temp}}

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="add">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</body>
@include('zng::common.footer')
@include('zng::{{name}}.js')
