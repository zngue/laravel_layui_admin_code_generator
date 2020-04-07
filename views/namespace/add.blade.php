@include('zng::common.header')
<body class="childrenBody">
    <form class="layui-form" action="">

        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" required  lay-verify="required" placeholder="请输入名称" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">地址</label>
            <div class="layui-input-block">
                <input type="text" name="address" required  lay-verify="required" placeholder="请输入地址" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">命名空间</label>
            <div class="layui-input-block">
                <input type="text" name="space" required  lay-verify="required" placeholder="请输入命名空间" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="1" title="正常" checked>
                <input type="radio" name="status" value="0" title="禁用" >
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="add">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</body>
@include('zng::common.footer')
@include('zng::namespace.js')
