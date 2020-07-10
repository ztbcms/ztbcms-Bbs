<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
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
                <el-table-column label="用户昵称" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.user_name }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="回复内容" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.content }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="回复时间" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.create_time }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="操作" align="center">
                    <template slot-scope="{row}">
                        <el-button type="primary" size="mini" @click="detail(row.id)">查看</el-button>
                        <el-button type="danger" size="mini" @click="remove(row.id)">删除</el-button>
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
                        where: {
                            article_id: '{:I("get.article_id")}'
                        }
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
                        var url = '{:U("Bbs/Article/getReplyList")}';
                        var data = that.listQuery;
                        that.httpGet(url, data, function(res){
                            that.list = res.data.items;
                            that.total = res.data.total_items;
                            that.listQuery.page = res.data.page;
                        });
                    },
                    detail: function(id){
                        var that = this;
                        layer.open({
                            type: 2,
                            title: '回复详情',
                            content: '{:U("Bbs/Article/reply")}&id='+id,
                            area: ['80%', '90%'],
                            end: function(){
                                that.getList();
                            }
                        })
                    },
                    remove: function(id){
                        var that = this;
                        that.$confirm('是否确定删除', '删除', {}).then(function(){
                            var url = '{:U("Bbs/Article/delReply")}';
                            that.httpPost(url, {id: id}, function(res){
                                if(res.status){
                                    that.getList();
                                }else{
                                    that.$message.error(res.msg);
                                }
                            });
                        }).catch(function(){});
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