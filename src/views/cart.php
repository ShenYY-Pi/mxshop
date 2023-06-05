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
    <title>购物车</title>
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
            购物车
            <el-button type="success" @click="order" size="mini">下单</el-button>
            <el-button type="danger" @click="cleanCart" size="mini">清空购物车</el-button>
            <el-table :data="cartList"
                      :summary-method="getSummaries"
                      show-summary>
                <el-table-column prop="name" label="名称" width="230">
                    <template slot-scope="scope">
                        <el-link target="_blank" :href="'product.php?id='+scope.row.id">{{scope.row.name}}</el-link>
                    </template>
                </el-table-column>
                <el-table-column prop="price" label="价格" width="150">
                    <template slot-scope="scope">
                        <span style="color: #ff5415;">￥{{scope.row.price}}</span>
                    </template>
                </el-table-column>
                <el-table-column prop="num" label="数量" width="140">
                    <template slot-scope="scope">
                        <div onselectstart="return false">
                            <i class="el-icon-remove-outline btn" @click="subNum(scope.row)"></i>
                            <span>{{scope.row.num}}</span>
                            <i class="el-icon-circle-plus-outline btn" @click="addNum(scope.row)"></i>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column>
                    <template slot-scope="scope" label="操作">
                        <el-button type="danger" @click="removeCart(scope.row)" size="mini">删除</el-button>
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
                cartList: [],
            }
        }, methods: {
            removeCart(row) {
                let that = this;
                let data = new URLSearchParams();
                data.append('id', row.id);
                axios.post('../dao/removeCart.php', data)
                    .then(function (res) {
                        if (res.data === 'success') {
                            that.getCartList();
                            that.$message.success('删除成功');
                        } else {
                            that.$message.error('错误');
                        }
                    })
                    .catch(function (err) {
                        that.$message.error('错误');
                        console.log(err);
                    });
            },
            addNum(row) {
                let that = this;
                row.num += 1;
                let data = new URLSearchParams();
                data.append('id', row.id);
                data.append('num', row.num);
                axios.post('../dao/cartNum.php', data)
                    .then(function (res) {
                        if (res.data === 'success') {

                        } else {
                            that.$message.error('错误');
                        }
                    })
                    .catch(function (err) {
                        that.$message.error('错误');
                        console.log(err);
                    });
            },
            subNum(row) {
                let that = this;
                row.num = (row.num > 1 ? row.num - 1 : row.num);

                let data = new URLSearchParams();
                data.append('id', row.id);
                data.append('num', row.num);
                axios.post('../dao/cartNum.php', data)
                    .then(function (res) {
                        if (res.data === 'success') {

                        } else {
                            that.$message.error('错误');
                        }
                    })
                    .catch(function (err) {
                        that.$message.error('错误');
                        console.log(err);
                    });
            },
            order() {
                if (this.cartList.length == 0) {
                    this.$message.error('购物车是空的');
                    return;
                }
                let data = new URLSearchParams();
                data.append('cart', JSON.stringify(this.cartList));
                let that = this;
                this.$confirm('确定提交订单?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post('../dao/addOrder.php', data)
                        .then(function (res) {
                            if (res.data === 'success') {
                                window.location.href = "order.php";
                            } else if (res.data === 'not_enough') {
                                that.$message.error('库存不足');
                            } else {
                                that.$message.error('错误');
                            }
                        })
                        .catch(function (err) {
                            that.$message.error('错误');
                            console.log(err);
                        });
                }).catch(() => {
                });
            },
            cleanCart() {
                if (this.cartList.length == 0) {
                    this.$message.error('购物车是空的');
                    return;
                }
                let that = this;
                this.$confirm('确定清空购物车?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post('../dao/cleanCart.php')
                        .then(function (res) {
                            if (res.data === 'success') {
                                that.$message.success('清空成功');
                                that.cartList = [];
                            } else {
                                that.$message.error('错误');
                            }
                        })
                        .catch(function (err) {
                            that.$message.error('错误');
                            console.log(err);
                        });
                }).catch(() => {

                });
            },
            getCartList() {
                let that = this;
                axios.post('../dao/getCart.php')
                    .then(function (res) {
                        if (res.data !== 'error') {
                            that.cartList = res.data;
                        } else {
                            that.$message.error('错误');
                        }
                    })
                    .catch(function (err) {
                        that.$message.error('错误');
                        console.log(err);
                    });
            },
            getSummaries(param) {
                const {columns, data} = param;
                const sums = [];
                columns.forEach((column, index) => {
                    if (index === 0) {
                        sums[index] = '总价';
                        return;
                    } else if (index === 1) {
                        let sum = 0;
                        for (let row of this.cartList) {
                            sum += row.price * row.num;
                        }
                        sums[index] = '￥' + sum + ' 元';
                    } else if (index === 2) {
                        let sum = 0;
                        for (let row of this.cartList) {
                            sum += row.num;
                        }
                        sums[index] = '共 ' + sum + ' 件商品'

                    } else {
                        sums[index] = '';
                    }
                });
                return sums;
            }
        },
        created() {
            this.getCartList();
        }
    })
</script>
</html>