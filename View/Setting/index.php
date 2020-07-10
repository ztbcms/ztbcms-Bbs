<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-row>
            <el-col :span="24">
                <div class="grid-content">
                    <el-form ref="form" :model="form" label-width="200px">
                        <el-card style="margin-top: 10px;">
                            <el-form-item label="发布帖子获得积分" required>
                                <el-input v-model="form.add_article_integral" placeholder="发布帖子获得积分" style="width: 200px;"></el-input>
                            </el-form-item>
                            <el-form-item label="回复帖子获得积分" required>
                                <el-input v-model="form.reply_article_integral" placeholder="回复帖子获得积分" style="width: 200px;"></el-input>
                            </el-form-item>
                            <el-form-item label="浏览帖子获得积分" required>
                                <el-input v-model="form.read_article_integral" placeholder="浏览帖子获得积分" style="width: 200px;"></el-input>
                            </el-form-item>
                            <el-form-item style="margin-top: 10px;">
                                <el-button type="primary" @click="onSubmit">保存</el-button>
                            </el-form-item>
                        </el-card>
                    </el-form>
                </div>
            </el-col>
        </el-row>
    </div>

    <script>
        $(document).ready(function () {
            window.__app = new Vue({
                el: '#app',
                data: {
                    form: {}
                },
                watch: {},
                filters: {},
                methods: {
                    getSetting: function(){
                        var that = this;
                        var url = '{:U("Bbs/Setting/getSetting")}';
                        var data = {};
                        that.httpGet(url, data, function(res){
                            if(res.status){
                                that.form = res.data;
                            }else{
                                layer.msg('获取失败', {time: 1000});
                            }
                        });
                    },
                    onSubmit: function(){
                        var that = this;
                        var url = '{:U("Bbs/Setting/setSetting")}';
                        var data = that.form;
                        that.httpPost(url, data, function(res){
                            if(res.status){
                                layer.msg('保存成功', {time: 1000});
                            }else{
                                layer.msg(res.msg, {time: 1000});
                            }
                        });
                    }
                },
                mounted: function () {
                    this.getSetting();
                }
            })
        })
    </script>
</block>
