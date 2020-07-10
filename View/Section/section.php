<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <el-row>
                <el-col :span="24">
                    <div class="grid-content">
                        <el-form ref="form" :model="form" label-width="150px">
                            <el-form-item label="版块名称" required>
                                <el-input v-model="form.name" placeholder="请输入版块名称" style="width: 200px;"></el-input>
                            </el-form-item>
                            <el-form-item label="上级分类" required>
                                <el-select v-model="form.pid" placeholder="请选择上级分类" clearable>
                                    <el-option label="顶级分类" value="0"></el-option>
                                    <volist name="sectionList" id="vo">
                                        <el-option label="{$vo.name}" value="{$vo.id}"></el-option>
                                    </volist>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="图标" required>
                                图片建议格式：500*500px，jpg\jpeg\png<br>
                                <template v-if="form.icon">
                                    <div class="imgListItem">
                                        <el-image :src="form.icon" style="width: 120px;height: 120px;"></el-image>
                                        <div class="deleteMask" @click="uploadIcon">
                                            <span style="line-height: 120px;font-size: 22px" class="el-icon-upload"></span>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="imgListItem">
                                        <div @click="uploadIcon" style="width: 120px;height: 120px;text-align: center;">
                                            <span style="line-height: 120px;font-size: 22px" class="el-icon-plus"></span>
                                        </div>
                                    </div>
                                </template>
                            </el-form-item>
                            <el-form-item label="公告内容" required>
                                <el-input v-model="form.affiche" type="textarea" placeholder="公告内容" rows="4" style="width: 400px;"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" @click="onSubmit">提交</el-button>
                                <el-button type="danger" @click="onCancel">关闭</el-button>
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
                        icon: ''
                    }
                },
                watch: {},
                filters: {},
                methods: {
                    ZTBCMS_UPLOAD_FILE: function(event){
                        this._uploadIcon(event);
                    },
                    _uploadIcon: function(event){
                        var that = this;
                        var files = event.detail.files;
                        that.form.icon = files[0].url;
                    },
                    uploadIcon: function(){
                        layer.open({
                            type: 2,
                            title: '',
                            closeBtn: false,
                            content: '{:U("Upload/UploadCenter/imageUploadPanel")}&max_upload=1',
                            area: ['70%', '80%']
                        })
                    },
                    getSection: function(){
                        var that = this;
                        var url = '{:U("Bbs/Section/getSection")}';
                        var data = {id: that.id};
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
                        var url = '{:U("Bbs/Section/addEditSection")}';
                        var data = that.form;
                        data.id = that.id;
                        that.httpPost(url, data, function(res){
                            if(res.status){
                                layer.msg('提交成功', {time: 1000}, function(){
                                    parent.layer.closeAll();
                                });
                            }else{
                                layer.msg(res.msg, {time: 1000});
                            }
                        });
                    },
                    onCancel: function(){
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    }
                },
                mounted: function () {
                    window.addEventListener('ZTBCMS_UPLOAD_FILE', this.ZTBCMS_UPLOAD_FILE.bind(this));
                    if(this.id){
                        this.getSection();
                    }
                }
            })
        })
    </script>
</block>
