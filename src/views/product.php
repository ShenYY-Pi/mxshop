<!DOCTYPE html>
<?php require('../static/utils/allFunc.php') ?>
<html>
<head>
    <meta charset="UTF-8">
    <!-- import CSS -->
    <!--<link rel="stylesheet" href="../static/css/element-ui.css">-->
    <link rel="stylesheet" href="https://unpkg.com/element-ui@2.9.1/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="../static/css/header.css">
    <title>MxShop </title>
    <style type="text/css">
        .price {
            width: 90%;
            height: 38px;
            color: #bf2600;
            font-size: 24px;
            font-weight: bold;
        }

        .btn {
            color: rgba(186, 47, 31, 0.86);
            cursor: pointer;
        }

        .num {
            color: #000;
            font-size: 16px;
        }
    </style>
</head>
<body>
<div id="app">
    <el-container>
        <?php require_once('./components/header.php') ?>
        <el-main>
            <div style="height: 300px;max-width:500px ;margin: 0 auto;">
                <el-image style="height: 300px;min-width: 300px;margin: 0 auto;" :src="'../uploads/images/'+productInfo.big_image" fit="contain"/>
            </div>
            <div style="width: 600px;margin:25px auto;">
                <h2>{{productInfo.name}}</h2>
                <div class="price" style="width: 600px;">
                    <span style="color: #bab7b4;font-size: 15px;margin-right: 15px;">价格</span>
                    ￥{{productInfo.price}}
                    <span style="font-size: 14px;width: 30px;color: #919191;"><i>库存:{{productInfo.number}}</i></span>
                    <span style="margin-left: 50px;" onselectstart="return false">
                        <i class="el-icon-remove-outline btn" @click="subNum"></i>
                        <span class="num">{{num}}</span>
                        <i class="el-icon-circle-plus-outline btn" @click="addNum"></i>
                        <el-button type="danger" plain size="mini" @click="addCart">加入购物车</el-button>
                    </span>

                </div>
                <p>{{productInfo.content}}</p>
            </div>
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
                imageList: [],
                productId: <?php echo getMapping('id') ?>,
                productInfo: null,
                num: 1,
            }
        }, methods: {
            addNum() {
                this.num += 1;
            },
            subNum() {
                this.num = (this.num > 1 ? this.num - 1 : this.num);
            },
            addCart() {
                let that = this;
                let data = {
                    num: this.num,
                    id: this.productId,
                    name: this.productInfo.name,
                    price: this.productInfo.price
                };

                axios.post('../dao/addCart.php', Qs.stringify(data))
                    .then(function (res) {
                        if (res.data === 'success') {
                            const h = that.$createElement;
                            that.$notify({
                                title: '成功添加到购物车',
                                offset: 60,
                                message: h('i', {style: 'color: teal'}, '添加' + that.num + '个' + that.productInfo.name + '到购物车')
                            });
                        } else if (res.data === 'error') {
                            that.$message.error('添加到购物车失败');
                        } else if (res.data === 'not_login') {
                            that.$message.error('请先登录');
                        }
                    })
                    .catch(function (err) {
                        console.log(err);
                    });
            },
            getProductInfo() {
                if (!this.productId) {
                    return;
                }
                let that = this;
                axios.get('../dao/getProductInfo.php', {params: {'productId': that.productId}}).then(function (res) {
                    // console.log("json:"+JSON.stringify(res.data));
                    if (res.data != 'error') {
                        that.productInfo = res.data;
                        console.clear();
                        document.title = 'MxShop '+that.productInfo.name;
                    }
                }).catch(function (err) {
                    console.log(err);
                });
            }
        },
        created() {
            this.getProductInfo();
        }
    })
</script>
</html>