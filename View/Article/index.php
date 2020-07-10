<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <div class="filter-container">
                <el-input v-model="listQuery.where.title" placeholder="帖子标题" style="width: 200px;"></el-input>
                <el-select v-model="listQuery.where.section_id" placeholder="请选择所属版块" clearable>
                    <volist name="sectionList" id="vo">
                        <el-option label="{$vo.name}" value="{$vo.id}"></el-option>
                    </volist>
                </el-select>
                <el-button class="filter-item" type="primary" style="margin-left: 10px;" @click="searchList">筛选</el-button>
            </div>
            <div class="filter-container">
                <el-button type="primary" plain>总量 {{total}}</el-button>
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
                <el-table-column label="标题" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.title }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="版块" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.section_name }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="查看量" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.read_num }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="回复量" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.reply_num }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="置顶" align="center">
                    <template slot-scope="{row}">
                        <el-switch v-model="row.is_top" active-value="1" inactive-value="0" @change="setTop(row.id, row.is_top)"></el-switch>
                    </template>
                </el-table-column>
                <el-table-column label="发布日期" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.create_time }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="审核状态" align="center">
                    <template slot-scope="{row}">
                        <template v-if="row.review_status == 1">
                            <el-tag type="warning">审核中</el-tag>
                        </template>
                        <template v-if="row.review_status == 2">
                            <el-tag type="success">已通过</el-tag>
                        </template>
                        <template v-if="row.review_status == 3">
                            <el-tag type="danger">已拒绝</el-tag>
                        </template>
                    </template>
                </el-table-column>
                <el-table-column label="操作" align="center" width="200" class-name="small-padding fixed-width">
                    <template slot-scope="{row}">
                        <template v-if="row.review_status == 1">
                            <el-button type="warning" size="mini" @click="detail(row.id)">审核内容</el-button>
                        </template>
                        <template v-else>
                            <el-button type="primary" size="mini" @click="detail(row.id)">帖子内容</el-button>
                        </template>
                        <el-button type="primary" size="mini" @click="reply(row.id)">回复列表</el-button>
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
                        var url = '{:U("Bbs/Article/getArticleList")}';
                        var data = that.listQuery;
                        that.httpGet(url, data, function(res){
                            that.list = res.data.items;
                            that.total = res.data.total_items;
                            that.listQuery.page = res.data.page;
                        });
                    },
                    setTop: function(id, is_top){
                        var that = this;
                        var url = '{:U("Bbs/Article/setTop")}';
                        var data = {id: id, is_top: is_top};
                        that.httpPost(url, data, function(res){
                            that.getList();
                        });
                    },
                    detail: function(id){
                        var that = this;
                        layer.open({
                            type: 2,
                            title: '帖子详情',
                            content: '{:U("Bbs/Article/article")}&id='+id,
                            area: ['100%', '100%'],
                            end: function(){
                                that.getList();
                            }
                        })
                    },
                    reply: function(id){
                        var that = this;
                        layer.open({
                            type: 2,
                            title: '回复列表',
                            content: '{:U("Bbs/Article/replyList")}&id='+id,
                            area: ['80%', '90%'],
                            end: function(){
                                //that.getList();
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