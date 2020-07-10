<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <el-row>
                <el-col :span="24">
                    <div class="grid-content">
                        <el-form ref="form" :model="form" label-width="150px">
                            <el-form-item label="所属版块" required>
                                <el-select v-model="form.section_id" placeholder="请选择所属版块" clearable>
                                    <volist name="sectionList" id="vo">
                                        <el-option label="{$vo.name}" value="{$vo.id}"></el-option>
                                    </volist>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="帖子内容" required>
                                <textarea id="editor" style="width: 375px;height: 500px;line-height: 0;"></textarea>
                            </el-form-item>
                            <el-form-item label="审核备注" v-if="form.review_status == 3">
                                <span style="color: red;">{{ form.review_remark }}</span>
                            </el-form-item>
                            <el-form-item v-if="form.review_status == 1">
                                <el-button type="success" @click="onSubmit(2)">审核通过</el-button>
                                <el-button type="warning" @click="onSubmit(3)">审核不通过</el-button>
                            </el-form-item>
                            <el-form-item v-else>
                                <el-button type="primary" @click="onSubmit(1)">保存</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
                <el-col :span="16"><div class="grid-content "></div></el-col>
            </el-row>
        </el-card>
    </div>

    <include file="Common/ueditor"/>
    <script>
        var editor = UE.getEditor('editor', ueditor_config);
        $(document).ready(function () {
            window.__app = new Vue({
                el: '#app',
                data: {
                    id: '{:I("get.id")}',
                    form: {
                    }
                },
                watch: {},
                filters: {},
                methods: {
                    getArticle: function(){
                        var that = this;
                        var url = '{:U("Bbs/Article/getArticle")}';
                        var data = {id: that.id};
                        that.httpGet(url, data, function(res){
                            if(res.status){
                                that.form = res.data;
                                editor.ready(function(){
                                    editor.setContent(res.data.content);
                                });
                            }else{
                                layer.msg('获取失败', {time: 1000});
                            }
                        });
                    },
                    onSubmit: function(submit_status){
                        var that = this;
                        if(submit_status == 3){
                            //审核不通过，需要填写原因
                            that.reject(submit_status);
                        }else{
                            var url = '{:U("Bbs/Article/editArticle")}';
                            var data = that.form;
                            data.id = that.id;
                            data.content = editor.getContent();
                            data.submit_status = submit_status;
                            that.httpPost(url, data, function(res){
                                if(res.status){
                                    layer.msg('提交成功', {time: 1000}, function(){
                                        parent.layer.closeAll();
                                    });
                                }else{
                                    layer.msg(res.msg, {time: 1000});
                                }
                            });
                        }
                    },
                    reject: function(submit_status){
                        var that = this;
                        that.$prompt('请输入拒绝原因', {
                            confirmButtonText: '提交',
                            cancelButtonText: '取消',
                            inputValue: '',
                            roundButton: true,
                            closeOnClickModal: false,
                            beforeClose: function(action, instance, done){
                                if(action == 'confirm'){
                                    var url = '{:U("Bbs/Article/editArticle")}';
                                    var data = that.form;
                                    data.id = that.id;
                                    data.submit_status = submit_status;
                                    data.review_remark = instance.inputValue;
                                    if(data.review_remark == ''){
                                        return;
                                    }
                                    that.httpPost(url, data, function(res){
                                        if(res.status){
                                            done();
                                            layer.msg('提交成功', {time: 1000}, function(){
                                                parent.layer.closeAll();
                                            });
                                        }else{
                                            layer.msg(res.msg, {time: 1000});
                                        }
                                    });
                                }else{
                                    done();
                                }
                            }
                        }).then(function(e){}).catch(function(){});
                    },
                    onCancel: function(){
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    }
                },
                mounted: function () {
                    if(this.id){
                        this.getArticle();
                    }
                }
            })
        })
    </script>
</block>
