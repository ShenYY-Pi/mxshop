<!DOCTYPE html>
<?php require('../static/utils/allFunc.php') ?>
<?php
if (!checkLogin()) {
    header("location:login.php");
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <!-- import CSS -->
    <!--<link rel="stylesheet" href="../static/css/element-ui.css">-->
    <link rel="stylesheet" href="https://unpkg.com/element-ui@2.9.1/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="../static/css/header.css">
    <title>订单</title>
    <style type="text/css">
        .btn {
            color: rgba(186, 47, 31, 0.86);
            cursor: pointer;
        }
    </style>
</head>
<body>
<div id="app">
    <el-container>
        <?php require_once('./components/header.php') ?>
        <el-main style="margin-left: 150px;">
            <el-table :data="orderList" stripe>
                <el-table-column type="expand">
                    <template slot-scope="props">
                        <el-table :data="props.row.products">
                            <el-table-column prop="name" label="名称" width="280">
                                <template slot-scope="scope">
                                    <el-link target="_blank" :href="'product.php?id='+scope.row.product_id">
                                        {{scope.row.name}}
                                    </el-link>
                                </template>
                            </el-table-column>
                            <el-table-column prop="price" label="单价" width="150">
                                <template slot-scope="scope">
                                    <span style="color: #ff5415;font-size: 14px;">￥{{scope.row.price}}</span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="number" label="数量"></el-table-column>
                        </el-table>
                    </template>
                </el-table-column>
                <el-table-column prop="order_id" label="订单ID" width="130">
                </el-table-column>
                <el-table-column prop="origin_price" label="总价" width="150">
                    <template slot-scope="scope">
                        <span style="color: #ff5415;">￥{{scope.row.origin_price}}</span>
                    </template>
                </el-table-column>
                <el-table-column prop="order_time" label="时间">
                    <template slot-scope="scope">
                        <i>{{scope.row.order_time}}</i>
                    </template>
                </el-table-column>
            </el-table>
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
                orderList: [],
            }
        }, methods: {
            getOrderList() {
                let that = this;
                axios.post('../dao/getOrderList.php')
                    .then(function (res) {
                        if (res.data !== 'error') {
                            that.orderList = res.data;
                        } else {
                            that.$message.error('错误');
                        }
                    })
                    .catch(function (err) {
                        that.$message.error('错误');
                        console.log(err);
                    });
            }
        },
        created() {
            this.getOrderList();
        }
    })
</script>
</html>