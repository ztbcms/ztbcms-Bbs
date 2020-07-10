<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <div class="filter-container">
                <el-input v-model="listQuery.where.name" placeholder="用户昵称" style="width: 200px;"></el-input>
                <el-button class="filter-item" type="primary" style="margin-left: 10px;" @click="searchList">筛选</el-button>
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
                <el-table-column label="用户头像" align="center">
                    <template slot-scope="{row}">
                        <el-image :src="row.avatar" style="width: 100px;height: 100px;"></el-image>
                    </template>
                </el-table-column>
                <el-table-column label="用户昵称" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.name }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="创建时间" align="center">
                    <template slot-scope="{row}">
                        <span>{{ row.create_time }}</span>
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
                        var url = '{:U("Bbs/User/getUserList")}';
                        var data = that.listQuery;
                        that.httpGet(url, data, function(res){
                            that.list = res.data.items;
                            that.total = res.data.total_items;
                            that.listQuery.page = res.data.page;
                        });
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