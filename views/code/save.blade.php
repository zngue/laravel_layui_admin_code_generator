@include('zng::common.header')
<body class="childrenBody">
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-block">
            <input type="text" name="title" required  lay-verify="required" placeholder="请输入名称（中文）" autocomplete="off" value="{{$data['title']}}" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">命名空间</label>
        <div id="space">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">模型选择</label>
        <div id="module">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">生成名称</label>
        <div class="layui-input-block">
            <input type="text" name="name" required  lay-verify="required" value="{{$data['name']}}" placeholder="请输入生成名称（英文）" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="正常"  {{ $data['status']==1?'checked': '' }} >
            <input type="radio" name="status" value="0" title="禁用" {{ $data['status']==0?'checked': '' }} >
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <input type="hidden" name="id" value="{{$data['id']}}">
            <button class="layui-btn" lay-submit lay-filter="save">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
</body>
@include('zng::common.footer')
<script>

</script>
@include('zng::code.js')
