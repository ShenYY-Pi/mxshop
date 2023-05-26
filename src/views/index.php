<!DOCTYPE html>
<?php require('../static/utils/allFunc.php') ?>
<html>
<head>
    <meta charset="UTF-8">
    <!-- import CSS -->
    <!--<link rel="stylesheet" href="../static/css/element-ui.css">-->
    <link rel="stylesheet" href="https://unpkg.com/element-ui@2.9.1/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="../static/css/header.css">
    <title>MxShop</title>
</head>
<body>
<div id="app">
    <el-container>
        <?php require_once('./components/header.php') ?>
        <el-main style="margin-left: 150px;">
            <el-tabs v-model="tabsValue" tab-position="left" @tab-click="handleChange">
                <el-tab-pane v-for="item in tagList" :name="item.id" :key="item.id" :label="item.name">
                    <el-table :data="productsList" v-loading="loading">
                        <el-table-column prop="image" label="图片" width="106px">
                            <template slot-scope="scope">
                                <el-popover placement="right-start"
                                            width="200"
                                            trigger="hover">
                                    <a target="_blank" :href="'product.php?id='+scope.row.product_id" slot="reference">
                                        <el-image style="width:80px;height: 80px"
                                                  :src="'../uploads/images/'+scope.row.image"
                                                  fit="cover"/>
                                    </a>
                                    <el-image style="width:200px;height: 200px" :src="'../uploads/images/'+scope.row.big_image"
                                              fit="cover">
                                    </el-image>
                                </el-popover>
                            </template>
                        </el-table-column>
                        <el-table-column prop="name" label="名称" width="230px">
                            <template slot-scope="scope">
                                <el-link target="_blank" :href="'product.php?id='+scope.row.product_id">
                                    {{scope.row.name}}
                                </el-link>
                            </template>
                        </el-table-column>
                        <el-table-column prop="price" label="价格" width="100px">
                            <template slot-scope="scope">
                                <span style="color: #ff5415;">￥{{scope.row.price}}</span>
                            </template>
                        </el-table-column>
                        <el-table-column prop="number" label="库存">
                            <template slot-scope="scope">
                                <span>库存:{{scope.row.number}}</span>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-tab-pane>
            </el-tabs>
        </el-main>
    </el-container>
</div>
</body>
<!--<script src="../static/js/vue.js"></script>-->
<!--<script src="../static/js/element-ui.js"></script>-->
<script src="https://unpkg.com/vue@2.6.10/dist/vue.js"></script>
<script src="https://unpkg.com/element-ui@2.9.1/lib/index.js"></script>
<script src="https://cdn.bootcss.com/axios/0.18.0/axios.js"></script>
<script src="https://cdn.bootcss.com/qs/6.7.0/qs.js"></script>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                loading: true,
                tabsValue: 0,
                tagList: [],
                productsList: [],
            }
        }, methods: {
            handleChange(tab, event) {
                this.getProductList(tab.name);
            },
            getTagList() {
                let that = this;
                axios.get('../dao/getTagList.php')
                    .then(function (res) {
                        // console.log(JSON.stringify(res.data));
                        if (res.data != 'error') {
                            that.tagList = res.data;
                            that.tabsValue = that.tagList[0].id;
                            that.getProductList(that.tabsValue);
                        }
                    })
                    .catch(function (err) {
                        console.log(err);
                    });
            },
            getProductList(tagId) {
                this.loading = true;
                let that = this;
                axios.get('../dao/getProductList.php', {params: {'tagId': tagId}}).then(function (res) {
                    if (res.data != 'error') {
                        that.productsList = res.data;
                        that.loading = false;
                    }
                }).catch(function (err) {
                    console.log(err);
                })
            }
        },
        created() {
            this.getTagList();
        }
    })
</script>
</html>