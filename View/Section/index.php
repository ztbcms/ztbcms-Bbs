<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <div class="filter-container">
                <el-input v-model="listQuery.where.name" placeholder="版块名称" style="width: 200px;"></el-input>
                <el-button class="filter-item" type="primary" style="margin-left: 10px;" @click="searchList">筛选</el-button>
            </div>
            <div class="filter-container">
                <el-button type="primary" plain>总量 {{total}}</el-button>
                <el-button type="success" @click="addSection">新增版块</el-button>
            </div>
            <el-table
                :key="tableKey"
                :data="list"
                border
                fit
                highlight-current-row
                style="width: 100%;"
                :default-sort="listQuery.sort"
                @sort-change="sortList"
            >
                <el-table-column label="ID" align="center" width="100">
                    <template slot-scope="{row}">
                        <span>{{ row.id }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="板块名称" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.name }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="板块图标" align="center">
                    <template slot-scope="{row}">
                        <el-image :src="row.icon" style="width: 60px;height: 60px;"></el-image>
                    </template>
                </el-table-column>
                <el-table-column label="排序" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.sort }}</span>
                        <i class="el-icon-edit" @click="updateSort(row.id, row.sort)" style="color: #409EFF;cursor: pointer;"></i>
                    </template>
                </el-table-column>
                <el-table-column label="是否显示" align="center">
                    <template slot-scope="{row}">
                        <el-switch v-model="row.is_show" active-value="1" inactive-value="0" @change="updateShow(row.id, row.is_show)"></el-switch>
                    </template>
                </el-table-column>
                <el-table-column label="创建日期" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.create_time }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="操作" align="center" width="200" class-name="small-padding fixed-width">
                    <template slot-scope="{row}">
                        <el-button type="primary" size="mini" @click="editSection(row.id)">编辑</el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div class="pagination-container">
                <el-pagination
                    background
                    layout="prev, pager, next, jumper"
                    :total="total"
                    v-show="total>0"
                    :current-page.sync="listQuery.page"
                    :page-size.sync="listQuery.limit"
                    @current-change="getList"
                >
                </el-pagination>
            </div>

        </el-card>
    </div>

    <style>
        .filter-container {
            padding-bottom: 10px;
        }

        .pagination-container {
            padding: 32px 16px;
        }
    </style>

    <script>
        $(document).ready(function () {
            new Vue({
                el: '#app',
                data: {
                    tableKey: 0,
                    list: [],
                    total: 0,
                    selectList: [],
                    listQuery: {
                        page: 1,
                        limit: 20,
                        where: {}
                    }
                },
                watch: {},
                filters: {
                    parseTime: function (time, format) {
                        if(time > 0){
                            return Ztbcms.formatTime(time, format)
                        }else{
                            return '-';
                        }
                    }
                },
                methods: {
                    searchList: function(){
                        this.listQuery.page = 1;
                        this.getList();
                    },
                    getList: function () {
                        var that = this;
                        var url = '{:U("Bbs/Section/getSectionList")}';
                        var data = that.listQuery;
                        that.httpGet(url, data, function(res){
                            that.list = res.data.items;
                            that.total = res.data.total_items;
                            that.listQuery.page = res.data.page;
                        });
                    },
                    updateShow: function(id, is_show){
                        var that = this;
                        var url = '{:U("Bbs/Section/updateShow")}';
                        var data = {id: id, is_show: is_show};
                        that.httpPost(url, data, function(res){
                            that.$message.success('修改成功');
                            that.getList();
                        });
                    },
                    updateSort: function(id, sort){
                        var that = this;
                        that.$prompt('请输入排序', {
                            confirmButtonText: '保存',
                            cancelButtonText: '取消',
                            inputValue: sort,
                            roundButton: true,
                            closeOnClickModal: false,
                            beforeClose: function(action, instance, done){
                                if(action == 'confirm'){
                                    var url = '{:U("Bbs/Section/updateSort")}';
                                    var data = {id: id, sort: instance.inputValue};
                                    that.httpPost(url, data, function(res){
                                        if(res.status){
                                            that.$message.success('修改成功');
                                            that.getList();
                                            done();
                                        }
                                    });
                                }else{
                                    done();
                                }
                            }
                        }).then(function(e){}).catch(function(){});
                    },
                    addSection: function(){
                        var that = this;
                        layer.open({
                            type: 2,
                            title: '新增版块',
                            content: '{:U("Bbs/Section/section")}',
                            area: ['50%', '70%'],
                            end: function(){
                                that.getList();
                            }
                        })
                    },
                    editSection: function(id){
                        var that = this;
                        layer.open({
                            type: 2,
                            title: '编辑版块',
                            content: '{:U("Bbs/Section/section")}&id='+id,
                            area: ['50%', '70%'],
                            end: function(){
                                that.getList();
                            }
                        })
                    }
                },
                mounted: function () {
                    var that = this;
                    that.getList();
                }
            })
        })
    </script>
</block>