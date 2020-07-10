<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <el-row>
                <el-col :span="24">
                    <div class="grid-content">
                        <el-form ref="form" :model="form" label-width="0">
                            <el-form-item label="">
                                <div v-html="form.content"></div>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
                <el-col :span="16"><div class="grid-content "></div></el-col>
            </el-row>
        </el-card>
    </div>

    <script>
        $(document).ready(function () {
            window.__app = new Vue({
                el: '#app',
                data: {
                    id: '{:I("get.id")}',
                    form: {
                        content: ''
                    }
                },
                watch: {},
                filters: {},
                methods: {
                    getReply: function(){
                        var that = this;
                        var url = '{:U("Bbs/Article/getReply")}';
                        var data = {id: that.id};
                        that.httpGet(url, data, function(res){
                            if(res.status){
                                that.form = res.data;
                            }else{
                                layer.msg('获取失败', {time: 1000});
                            }
                        });
                    },
                    onCancel: function(){
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    }
                },
                mounted: function () {
                    if(this.id){
                        this.getReply();
                    }
                }
            })
        })
    </script>
</block>
